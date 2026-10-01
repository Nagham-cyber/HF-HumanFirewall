@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="card p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="material-icons text-3xl text-[#4ADE80]">people</span>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-[#4ADE80]/10 text-[#4ADE80]">Total</span>
        </div>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total_users'] }}</p>
        <p class="text-xs text-[#94A3B8] mt-1">Registered Users</p>
        <p class="text-[10px] text-[#94A3B8]/60 mt-1">+{{ $weeklyStats['new_users'] }} this week</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="material-icons text-3xl text-[#60A5FA]">menu_book</span>
        </div>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['total_scenarios'] }}</p>
        <p class="text-xs text-[#94A3B8] mt-1">Scenarios</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="material-icons text-3xl text-orange-400">assignment</span>
        </div>
        <p class="text-3xl font-black text-orange-400">{{ $stats['total_tasks'] }}</p>
        <p class="text-xs text-[#94A3B8] mt-1">Cyber Tasks</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="material-icons text-3xl text-purple-400">bolt</span>
        </div>
        <p class="text-3xl font-black text-purple-400">{{ $stats['total_challenges'] }}</p>
        <p class="text-xs text-[#94A3B8] mt-1">Daily Challenges</p>
    </div>

</div>

<!-- Second Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-2xl text-[#4ADE80]">check_circle</span>
            <p class="text-xs text-[#94A3B8] uppercase font-bold">Completed Tasks</p>
        </div>
        <p class="text-2xl font-black text-[#4ADE80]">{{ $stats['completed_submissions'] }}</p>
        <p class="text-[10px] text-[#94A3B8] mt-1">+{{ $weeklyStats['completed_tasks'] }} this week</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-2xl text-orange-400">pending</span>
            <p class="text-xs text-[#94A3B8] uppercase font-bold">In Progress</p>
        </div>
        <p class="text-2xl font-black text-orange-400">{{ $stats['in_progress_submissions'] }}</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-2xl text-[#60A5FA]">stars</span>
            <p class="text-xs text-[#94A3B8] uppercase font-bold">Total XP</p>
        </div>
        <p class="text-2xl font-black text-[#60A5FA]">{{ number_format($stats['total_xp_awarded']) }}</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-2xl text-red-400">bug_report</span>
            <p class="text-xs text-[#94A3B8] uppercase font-bold">Mistakes</p>
        </div>
        <p class="text-2xl font-black text-red-400">{{ $stats['total_mistakes'] }}</p>
    </div>

</div>

<!-- Top Users & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Top Users -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm flex items-center gap-2">
                <span class="material-icons text-[#4ADE80]">emoji_events</span>
                Top 10 Champions
            </h3>
            <a href="#" class="text-xs text-[#4ADE80] hover:text-[#60A5FA]">View All</a>
        </div>
        <div class="space-y-2">
            @foreach($topUsers as $index => $user)
            <div class="flex items-center justify-between p-3 bg-[#0A0A0F]/50 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold w-5
                        @if($index == 0) text-yellow-400
                        @elseif($index == 1) text-gray-300
                        @elseif($index == 2) text-amber-600
                        @else text-[#94A3B8] @endif">
                        #{{ $index + 1 }}
                    </span>
                    <div class="w-8 h-8 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold text-xs">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold">{{ $user->name }}</p>
                        <p class="text-[10px] text-[#94A3B8]">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-[#4ADE80]">{{ $user->xp_points }} XP</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Submissions -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm flex items-center gap-2">
                <span class="material-icons text-[#60A5FA]">schedule</span>
                Recent Task Submissions
            </h3>
            <a href="#" class="text-xs text-[#4ADE80] hover:text-[#60A5FA]">View All</a>
        </div>
        <div class="space-y-2">
            @forelse($recentSubmissions as $sub)
            <div class="flex items-center justify-between p-3 bg-[#0A0A0F]/50 rounded-xl">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-8 h-8 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold text-xs shrink-0">
                        {{ substr($sub->user_name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold truncate">{{ $sub->task_title }}</p>
                        <p class="text-[10px] text-[#94A3B8]">{{ $sub->user_name }} • {{ $sub->reference_code }}</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-1 rounded-full shrink-0
                    {{ $sub->status == 'completed' ? 'bg-[#4ADE80]/10 text-[#4ADE80]' : 'bg-orange-500/10 text-orange-400' }}">
                    {{ ucfirst($sub->status) }}
                </span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No submissions yet.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Recent Users & Tasks Distribution -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Users -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">person_add</span>
            Recently Registered Users
        </h3>
        <div class="space-y-2">
            @forelse($recentUsers as $user)
            <div class="flex items-center justify-between p-3 bg-[#0A0A0F]/50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold text-xs">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold">{{ $user->name }}</p>
                        <p class="text-[10px] text-[#94A3B8]">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No users yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Tasks by Section -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#60A5FA]">pie_chart</span>
            Cyber Tasks by Section
        </h3>
        <div class="space-y-3">
            @foreach($tasksBySection as $section)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-[#94A3B8] font-bold uppercase">{{ str_replace('_', ' ', $section->section) }}</span>
                    <span class="text-xs font-bold text-[#4ADE80]">{{ $section->total }}</span>
                </div>
                <div class="h-1.5 bg-[#4ADE80]/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] rounded-full" 
                         style="width: {{ ($section->total / 300) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection