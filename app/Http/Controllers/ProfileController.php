<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // إحصائيات
        $stats = [
            'total_points' => $user->xp_points ?? 0,
            'security_level' => $user->security_rank ?? 'Security Novice',
            'streak_days' => $user->streak_days ?? 0,
            'completed_scenarios' => 0,
            'badges_earned' => 0,
            'certificates_earned' => 0,
        ];
        
        $earnedBadges = collect([]);
        $earnedCertificates = collect([]);
        
        try {
            $stats['completed_scenarios'] = DB::table('user_progress')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count();
            
            // الشارات المكتسبة
            $earnedBadges = DB::table('user_badges')
                ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
                ->where('user_badges.user_id', $user->id)
                ->select('badges.name', 'badges.icon', 'badges.color', 'badges.tier', 'user_badges.earned_at')
                ->orderBy('user_badges.earned_at', 'desc')
                ->get();
            
            $stats['badges_earned'] = $earnedBadges->count();
            
            // الشهادات
            $earnedCertificates = DB::table('user_progress')
                ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                ->where('user_progress.user_id', $user->id)
                ->where('user_progress.status', 'completed')
                ->select('scenarios.user_type')
                ->distinct()
                ->get();
            
            $stats['certificates_earned'] = $earnedCertificates->count();
            
        } catch (\Exception $e) {}

        return view('profile.index', compact('user', 'stats', 'earnedBadges', 'earnedCertificates'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::id());
        
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        $user->update(['password' => Hash::make($validated['new_password'])]);
        
        return back()->with('success', 'Password updated!');
    }

    public function destroy(Request $request)
    {
        $user = User::find(Auth::id());
        
        if (!Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }
        
        try {
            DB::table('user_progress')->where('user_id', $user->id)->delete();
            DB::table('user_badges')->where('user_id', $user->id)->delete();
            DB::table('user_mistakes')->where('user_id', $user->id)->delete();
            DB::table('user_awareness_levels')->where('user_id', $user->id)->delete();
            DB::table('notifications')->where('user_id', $user->id)->delete();
            DB::table('daily_challenge_answers')->where('user_id', $user->id)->delete();
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            $user->delete();
            
            return redirect('/')->with('success', 'Account deleted.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}