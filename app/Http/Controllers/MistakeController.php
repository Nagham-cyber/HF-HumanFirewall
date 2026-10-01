<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MistakeController extends Controller
{
    /**
     * عرض قائمة الأخطاء
     */
    public function index()
    {
        $user = Auth::user();
        $mistakes = collect([]);
        $stats = ['total_mistakes' => 0, 'reviewed' => 0, 'unreviewed' => 0];
        $weakAreas = [];

        try {
            $mistakes = DB::table('user_mistakes')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $stats['total_mistakes'] = $mistakes->count();
            $stats['reviewed'] = $mistakes->where('is_reviewed', true)->count();
            $stats['unreviewed'] = $stats['total_mistakes'] - $stats['reviewed'];

            $categoryCounts = [];
            foreach ($mistakes as $mistake) {
                $cat = $mistake->category ?? 'general';
                if (!isset($categoryCounts[$cat])) {
                    $categoryCounts[$cat] = 0;
                }
                $categoryCounts[$cat]++;
            }

            $total = array_sum($categoryCounts) ?: 1;
            foreach ($categoryCounts as $cat => $count) {
                $weakAreas[] = [
                    'category' => $cat,
                    'count' => $count,
                    'percentage' => round(($count / $total) * 100),
                ];
            }
        } catch (\Exception $e) {
            // ignore
        }

        return view('mistakes.index', compact('mistakes', 'stats', 'weakAreas'));
    }

    /**
     * عرض تفاصيل خطأ محدد
     */
    public function show($id)
    {
        $user = Auth::user();

        $mistake = DB::table('user_mistakes')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$mistake) {
            return redirect('/mistakes')->with('error', 'Mistake not found');
        }

        // ✅ استخدام البيانات المحفوظة
        $scenarioText = $mistake->scenario_text ?? 'Scenario not available';
        $question = $mistake->question ?? 'Question not available';
        $explanation = $mistake->explanation ?? 'No explanation available.';

        // ✅ استرجاع الخيارات من JSON أو بناء خيارين
        $options = [];
        if (!empty($mistake->options)) {
            $options = json_decode($mistake->options, true);
            if (!is_array($options)) {
                $options = [];
            }
        }

        // إذا لم توجد خيارات، ابني خيارين من user_answer و correct_answer
        if (empty($options)) {
            $options = [
                ['text' => $mistake->user_answer ?? 'Your answer', 'correct' => false],
                ['text' => $mistake->correct_answer ?? 'Correct answer', 'correct' => true],
            ];
        }

        // توليد سؤال إعادة
        $retryQuestion = $this->generateRetryQuestion($question, $options);

        return view('mistakes.show', compact(
            'mistake',
            'scenarioText',
            'question',
            'options',
            'explanation',
            'retryQuestion'
        ));
    }

    /**
     * تحديث حالة الخطأ إلى "تمت مراجعته"
     */
    public function markReviewed($id)
    {
        try {
            $updated = DB::table('user_mistakes')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->update([
                    'is_reviewed' => true,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => $updated > 0,
                'message' => $updated > 0 ? 'Marked as reviewed' : 'No record found',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * توليد سؤال إعادة بصيغة مختلفة
     */
    private function generateRetryQuestion($originalQuestion, $options)
    {
        $questionVariations = [
            'Think again: ' . $originalQuestion,
            'Imagine this situation: ' . $originalQuestion,
            'Quick check: ' . $originalQuestion,
        ];

        $newQuestion = $questionVariations[array_rand($questionVariations)];

        $shuffledOptions = $options;
        shuffle($shuffledOptions);

        return [
            'question' => $newQuestion,
            'options' => $shuffledOptions,
        ];
    }
}