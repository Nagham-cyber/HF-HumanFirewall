<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // إحصائيات حية
        $stats = [
            'scenarios' => DB::table('scenarios')->where('is_active', true)->count(),
            'tasks' => DB::table('cyber_tasks')->where('is_active', true)->count(),
            'users' => DB::table('users')->count(),
            'challenges' => DB::table('challenges')->count(),
            'categories' => DB::table('categories')->where('is_active', true)->count(),
            'badges' => DB::table('badges')->count(),
        ];

        // Top 3 Champions
        $topChampions = DB::table('users')
            ->select('id', 'name', 'xp_points', 'security_rank')
            ->orderBy('xp_points', 'desc')
            ->limit(3)
            ->get();

        return view('welcome', compact('stats', 'topChampions'));
    }
}