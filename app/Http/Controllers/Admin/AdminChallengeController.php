<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminChallengeController extends Controller
{
    /**
     * قائمة التحديات
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $difficulty = $request->get('difficulty');

        $query = DB::table('daily_challenges');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('scenario', 'like', "%{$search}%");
            });
        }

        if ($difficulty && $difficulty !== 'all') {
            $query->where('difficulty', $difficulty);
        }

        $challenges = $query->orderBy('challenge_date', 'desc')->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => DB::table('daily_challenges')->count(),
            'easy' => DB::table('daily_challenges')->where('difficulty', 'easy')->count(),
            'medium' => DB::table('daily_challenges')->where('difficulty', 'medium')->count(),
            'hard' => DB::table('daily_challenges')->where('difficulty', 'hard')->count(),
            'today' => DB::table('daily_challenges')->whereDate('challenge_date', today())->count(),
        ];

        return view('admin.challenges.index', compact('challenges', 'stats', 'search', 'difficulty'));
    }

    /**
     * صفحة إضافة تحدي
     */
    public function create()
    {
        return view('admin.challenges.create');
    }

    /**
     * حفظ تحدي واحد
     */
    public function store(Request $request)
    {
        $request->validate([
            'difficulty' => 'required|in:easy,medium,hard',
            'challenge_date' => 'required|date',
            'scenario' => 'required|string',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
            'explanation' => 'required|string',
        ]);

        // بناء الخيارات
        $options = [];
        foreach ($request->options as $index => $option) {
            if (!empty(trim($option))) {
                $options[] = [
                    'text' => trim($option),
                    'correct' => ($index == $request->correct_option),
                ];
            }
        }

        // النقاط حسب الصعوبة
        $pointsMap = ['easy' => 5, 'medium' => 10, 'hard' => 15];

        DB::table('daily_challenges')->insert([
            'difficulty' => $request->difficulty,
            'points' => $pointsMap[$request->difficulty],
            'scenario' => $request->scenario,
            'question' => $request->question,
            'options' => json_encode($options),
            'explanation' => $request->explanation,
            'challenge_date' => $request->challenge_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge created successfully!');
    }

    /**
     * حفظ 3 تحديات مرة واحدة (Easy + Medium + Hard)
     */
    public function storeThree(Request $request)
    {
        $request->validate([
            'challenge_date' => 'required|date',
            // Easy
            'easy_scenario' => 'required|string',
            'easy_question' => 'required|string',
            'easy_options' => 'required|array|min:2',
            'easy_correct' => 'required|integer|min:0',
            'easy_explanation' => 'required|string',
            // Medium
            'medium_scenario' => 'required|string',
            'medium_question' => 'required|string',
            'medium_options' => 'required|array|min:2',
            'medium_correct' => 'required|integer|min:0',
            'medium_explanation' => 'required|string',
            // Hard
            'hard_scenario' => 'required|string',
            'hard_question' => 'required|string',
            'hard_options' => 'required|array|min:2',
            'hard_correct' => 'required|integer|min:0',
            'hard_explanation' => 'required|string',
        ]);

        $levels = [
            ['easy', 5, 'easy_'],
            ['medium', 10, 'medium_'],
            ['hard', 15, 'hard_'],
        ];

        foreach ($levels as [$level, $points, $prefix]) {
            $options = [];
            foreach ($request->{$prefix . 'options'} as $index => $option) {
                if (!empty(trim($option))) {
                    $options[] = [
                        'text' => trim($option),
                        'correct' => ($index == $request->{$prefix . 'correct'}),
                    ];
                }
            }

            DB::table('daily_challenges')->insert([
                'difficulty' => $level,
                'points' => $points,
                'scenario' => $request->{$prefix . 'scenario'},
                'question' => $request->{$prefix . 'question'},
                'options' => json_encode($options),
                'explanation' => $request->{$prefix . 'explanation'},
                'challenge_date' => $request->challenge_date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.challenges.index')->with('success', 'All 3 challenges created successfully!');
    }

    /**
     * حذف تحدي
     */
    public function destroy($id)
    {
        DB::table('daily_challenges')->where('id', $id)->delete();
        return redirect()->route('admin.challenges.index')->with('success', 'Challenge deleted successfully!');
    }
}