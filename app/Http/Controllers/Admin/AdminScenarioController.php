<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminScenarioController extends Controller
{
    /**
     * الفئات المتاحة
     */
       private function getCategories()
    {
        // قراءة الفئات من جدول categories (type = scenario أو both)
        $categories = DB::table('categories')
            ->whereIn('type', ['scenario', 'both'])
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        if ($categories->isEmpty()) {
            // fallback إذا لم توجد فئات
            return [
                'phishing' => 'Phishing',
                'password' => 'Password Security',
                'malware' => 'Malware',
                'social_engineering' => 'Social Engineering',
                'network' => 'Network Security',
                'physical' => 'Physical Security',
                'data' => 'Data Security',
                'cloud' => 'Cloud Security',
                'mobile' => 'Mobile Security',
                'insider' => 'Insider Threat',
            ];
        }

        $result = [];
        foreach ($categories as $cat) {
            $result[$cat->slug] = $cat->name;
        }
        return $result;
    }

    /**
     * النقاط حسب المستوى
     */
    private function getPointsByLevel($level)
    {
        return [
            'easy' => 10,
            'medium' => 20,
            'hard' => 30,
            'expert' => 50,
        ][$level] ?? 10;
    }

    /**
     * قائمة السيناريوهات
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $userType = $request->get('user_type');
        $level = $request->get('level');

        $query = DB::table('scenarios');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($userType && $userType !== 'all') {
            $query->where('user_type', $userType);
        }

        if ($level && $level !== 'all') {
            $query->where('level', $level);
        }

        $scenarios = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => DB::table('scenarios')->count(),
            'general' => DB::table('scenarios')->where('user_type', 'general')->count(),
            'it_professional' => DB::table('scenarios')->where('user_type', 'it_professional')->count(),
            'security_expert' => DB::table('scenarios')->where('user_type', 'security_expert')->count(),
        ];

        return view('admin.scenarios.index', compact('scenarios', 'stats', 'search', 'userType', 'level'));
    }

    /**
     * صفحة إضافة سيناريو
     */
    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.scenarios.create', compact('categories'));
    }

    /**
     * حفظ سيناريو جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'user_type' => 'required|in:general,it_professional,security_expert',
            'level' => 'required|in:easy,medium,hard,expert',
            'estimated_minutes' => 'required|integer|min:1|max:60',
            'scenario_text' => 'required|string',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
            'explanation' => 'required|string',
        ]);

        // النقاط حسب المستوى
        $points = $this->getPointsByLevel($request->level);

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

        // محتوى JSON — كل شيء يُخزَّن هنا
        $content = [
            'scenario' => $request->scenario_text,
            'question' => $request->question,
            'options' => $options,
            'explanation' => $request->explanation,
            'points' => $points,
        ];

        // خرائط
        $difficultyMap = [
            'easy' => 'beginner',
            'medium' => 'intermediate',
            'hard' => 'advanced',
            'expert' => 'advanced',
        ];

        $stageMap = [
            'easy' => 1,
            'medium' => 2,
            'hard' => 3,
            'expert' => 4,
        ];

        DB::table('scenarios')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'user_type' => $request->user_type,
            'level' => $request->level,
            'stage' => $stageMap[$request->level],
            'difficulty' => $difficultyMap[$request->level],
            'estimated_minutes' => $request->estimated_minutes,
            'uuid' => (string) Str::uuid(),
            'is_active' => true,
            'is_template' => false,
            'version' => 1,
            'content' => json_encode($content),
            'metadata' => json_encode(['points' => $points]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.scenarios.index')->with('success', 'Scenario created successfully!');
    }

    /**
     * حذف سيناريو
     */
    public function destroy($id)
    {
        DB::table('scenarios')->where('id', $id)->delete();
        return redirect()->route('admin.scenarios.index')->with('success', 'Scenario deleted successfully!');
    }

    /**
     * حذف بواسطة العنوان
     */
    public function deleteByTitle(Request $request)
    {
        $title = $request->get('title');
        $deleted = DB::table('scenarios')->where('title', $title)->delete();

        if ($deleted > 0) {
            return back()->with('success', "Deleted {$deleted} scenario(s).");
        }

        return back()->with('error', 'No scenario found with this title.');
    }
}