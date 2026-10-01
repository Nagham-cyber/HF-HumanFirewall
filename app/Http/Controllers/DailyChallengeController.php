<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyChallengeController extends Controller
{
    /**
     * عرض تحديات اليوم
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // هل أكمل تحديات اليوم؟
        $completedToday = DB::table('daily_challenge_answers')
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();

        // جلب تحديات اليوم
        $challenges = DB::table('daily_challenges')
            ->where('challenge_date', $today->format('Y-m-d'))
            ->orderByRaw("CASE difficulty WHEN 'easy' THEN 1 WHEN 'medium' THEN 2 WHEN 'hard' THEN 3 END")
            ->get();

        // إذا لم توجد تحديات اليوم، أنشئها تلقائياً
        if ($challenges->isEmpty()) {
            $this->generateTodayChallenges();
            $challenges = DB::table('daily_challenges')
                ->where('challenge_date', $today->format('Y-m-d'))
                ->orderByRaw("CASE difficulty WHEN 'easy' THEN 1 WHEN 'medium' THEN 2 WHEN 'hard' THEN 3 END")
                ->get();
        }

        // حساب الـ streak
              // حساب الـ streak
        $streakDays = $this->calculateStreak($user->id);

        // تحديث الـ streak في قاعدة البيانات
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'streak_days' => $streakDays,
                'updated_at' => now(),
            ]);

        return view('challenges.index', compact('challenges', 'streakDays', 'completedToday'));
    }

    /**
     * معالجة إجابات كل التحديات
     */
    public function submitAll(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // ⚠️ التحقق: هل أكمل التحدي اليوم؟
        $alreadyCompleted = DB::table('daily_challenge_answers')
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($alreadyCompleted) {
            return response()->json([
                'success' => false,
                'already_completed' => true,
                'message' => 'You have already completed today\'s challenge. Come back tomorrow!',
                'correct_count' => 0,
                'total_challenges' => 3,
                'total_points' => 0,
                'streak' => $this->calculateStreak($user->id),
            ]);
        }

        // التحقق من البيانات
        $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*.challenge_id' => 'required|integer',
            'answers.*.selected_option' => 'required|integer|min:0',
        ]);

        $answers = $request->input('answers', []);
        $correctCount = 0;
        $totalPoints = 0;
        $totalChallenges = count($answers);
        $results = [];

        foreach ($answers as $answer) {
            $challenge = DB::table('daily_challenges')->find($answer['challenge_id']);
            if (!$challenge) {
                continue;
            }

            // 🔐 التحقق من الإجابة الصحيحة من قاعدة البيانات
            $options = is_string($challenge->options) 
                ? json_decode($challenge->options, true) 
                : $challenge->options;

            $selectedIndex = (int) $answer['selected_option'];
            $correctIndex = null;

            foreach ($options as $index => $option) {
                if (!empty($option['correct'])) {
                    $correctIndex = $index;
                    break;
                }
            }

            $isCorrect = ($selectedIndex === $correctIndex);

            // حفظ الإجابة
            DB::table('daily_challenge_answers')->insert([
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'is_correct' => $isCorrect,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($isCorrect) {
                $correctCount++;
                $totalPoints += (int) $challenge->points;
            } else {
                // ❌ إضافة الخطأ إلى بنك الأخطاء
                $this->recordMistake($user->id, $challenge, $selectedIndex, $correctIndex, $options);
            }

            $results[] = [
                'challenge_id' => $challenge->id,
                'selected' => $selectedIndex,
                'correct' => $correctIndex,
                'is_correct' => $isCorrect,
            ];
        }

        if ($totalPoints > 0) {
            DB::table('users')->where('id', $user->id)->increment('xp_points', $totalPoints);
        }

        $streak = $this->calculateStreak($user->id);

        // تحديث الـ streak في قاعدة البيانات
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'streak_days' => $streak,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => $correctCount === $totalChallenges,
            'correct_count' => $correctCount,
            'total_challenges' => $totalChallenges,
            'total_points' => $totalPoints,
            'streak' => $streak,
            'results' => $results,
        ]);
    }

      /**
     * تسجيل الخطأ في بنك الأخطاء (user_mistakes)
     */
    private function recordMistake($userId, $challenge, $selectedIndex, $correctIndex, $options)
    {
        if (!\Schema::hasTable('user_mistakes')) {
            return;
        }

        $selectedText = $options[$selectedIndex]['text'] ?? 'Unknown';
        $correctText = $options[$correctIndex]['text'] ?? 'Unknown';

        // تحقق من وجود نفس الخطأ
        $existing = DB::table('user_mistakes')
            ->where('user_id', $userId)
            ->where('challenge_id', $challenge->id)
            ->where('mistake_type', 'daily_challenge')
            ->first();

        if ($existing) {
            DB::table('user_mistakes')
                ->where('id', $existing->id)
                ->update([
                    'user_answer' => $selectedText,
                    'correct_answer' => $correctText,
                    'updated_at' => now(),
                ]);
            return;
        }

        DB::table('user_mistakes')->insert([
            'user_id' => $userId,
            'scenario_id' => $challenge->id,
            'challenge_id' => $challenge->id,
            'category' => $challenge->difficulty ?? 'general',
            'mistake_type' => 'daily_challenge',
            'scenario_text' => $challenge->scenario,
            'question' => $challenge->question,
            'user_answer' => $selectedText,
            'correct_answer' => $correctText,
            'options' => json_encode($options),
            'explanation' => $challenge->explanation ?? 'No explanation available.',
            'is_reviewed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * حساب عدد أيام الاستمرارية
     */
    private function calculateStreak($userId)
    {
        $dates = DB::table('daily_challenge_answers')
            ->where('user_id', $userId)
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $streak = 0;
        $checkDate = Carbon::today();

        if ($dates[0] !== $checkDate->format('Y-m-d')) {
            $checkDate = $checkDate->copy()->subDay();
        }

        foreach ($dates as $date) {
            if ($date === $checkDate->format('Y-m-d')) {
                $streak++;
                $checkDate = $checkDate->copy()->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * توليد تحديات اليوم تلقائياً
     */
    private function generateTodayChallenges()
    {
        $today = Carbon::today();

        DB::table('daily_challenges')
            ->where('challenge_date', $today->format('Y-m-d'))
            ->delete();

        // قراءة التحديات من جدول challenges
        $easyChallenge = DB::table('challenges')
            ->where('difficulty', 'easy')
            ->inRandomOrder()
            ->first();

        $mediumChallenge = DB::table('challenges')
            ->where('difficulty', 'medium')
            ->inRandomOrder()
            ->first();

        $hardChallenge = DB::table('challenges')
            ->where('difficulty', 'hard')
            ->inRandomOrder()
            ->first();

        // إذا لم توجد، استخدم default
        if (!$easyChallenge) {
            $easyChallenge = (object) [
                'scenario' => 'You receive an email from an unknown sender with an attachment named "Invoice.pdf.exe".',
                'question' => 'What should you do?',
                'options' => json_encode([
                    ['text' => 'Open it to see the invoice', 'correct' => false],
                    ['text' => 'Delete it and report to IT', 'correct' => true],
                    ['text' => 'Rename it and open', 'correct' => false],
                    ['text' => 'Forward it to a colleague', 'correct' => false],
                ]),
                'explanation' => 'Files with double extensions are malware.',
            ];
        }

        if (!$mediumChallenge) {
            $mediumChallenge = (object) [
                'scenario' => 'You receive an email from your "CEO" asking for an urgent wire transfer.',
                'question' => 'What should you do first?',
                'options' => json_encode([
                    ['text' => 'Process it immediately', 'correct' => false],
                    ['text' => 'Verify via phone call with the CEO', 'correct' => true],
                    ['text' => 'Reply to the email', 'correct' => false],
                    ['text' => 'Forward to colleague', 'correct' => false],
                ]),
                'explanation' => 'This is a BEC attack.',
            ];
        }

        if (!$hardChallenge) {
            $hardChallenge = (object) [
                'scenario' => 'You receive a 2FA code request you did not initiate.',
                'question' => 'What does this mean?',
                'options' => json_encode([
                    ['text' => 'A system glitch', 'correct' => false],
                    ['text' => 'Someone is trying to access your account', 'correct' => true],
                    ['text' => 'Ignore it', 'correct' => false],
                    ['text' => 'Share the code', 'correct' => false],
                ]),
                'explanation' => 'Someone has your password.',
            ];
        }

        DB::table('daily_challenges')->insert([
            [
                'difficulty' => 'easy',
                'points' => 5,
                'scenario' => $easyChallenge->scenario,
                'question' => $easyChallenge->question,
                'options' => $easyChallenge->options,
                'explanation' => $easyChallenge->explanation,
                'challenge_date' => $today->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'difficulty' => 'medium',
                'points' => 10,
                'scenario' => $mediumChallenge->scenario,
                'question' => $mediumChallenge->question,
                'options' => $mediumChallenge->options,
                'explanation' => $mediumChallenge->explanation,
                'challenge_date' => $today->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'difficulty' => 'hard',
                'points' => 15,
                'scenario' => $hardChallenge->scenario,
                'question' => $hardChallenge->question,
                'options' => $hardChallenge->options,
                'explanation' => $hardChallenge->explanation,
                'challenge_date' => $today->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}