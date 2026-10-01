@extends('admin.layouts.app')

@section('title', 'Reports')

@section('content')

<!-- Stats Overview -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="card p-4 text-center">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">people</span>
        <p class="text-2xl font-black text-[#4ADE80]">{{ $stats['total_users'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Users</p>
    </div>
    <div class="card p-4 text-center">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">menu_book</span>
        <p class="text-2xl font-black text-[#60A5FA]">{{ $stats['total_scenarios'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Scenarios</p>
    </div>
    <div class="card p-4 text-center">
        <span class="material-icons text-2xl text-orange-400 mb-2">assignment</span>
        <p class="text-2xl font-black text-orange-400">{{ $stats['total_tasks'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Tasks</p>
    </div>
    <div class="card p-4 text-center">
        <span class="material-icons text-2xl text-purple-400 mb-2">fact_check</span>
        <p class="text-2xl font-black text-purple-400">{{ $stats['total_submissions'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Submissions</p>
    </div>
    <div class="card p-4 text-center">
        <span class="material-icons text-2xl text-red-400 mb-2">bug_report</span>
        <p class="text-2xl font-black text-red-400">{{ $stats['total_mistakes'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Mistakes</p>
    </div>
    <div class="card p-4 text-center">
        <span class="material-icons text-2xl text-yellow-400 mb-2">stars</span>
        <p class="text-2xl font-black text-yellow-400">{{ number_format($stats['total_xp']) }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Total XP</p>
    </div>
</div>

<!-- Export Cards -->
<h2 class="text-lg font-black mb-4 flex items-center gap-2">
    <span class="material-icons text-[#4ADE80]">download</span>
    Export Reports (CSV)
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <!-- Users Report -->
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-3xl text-[#4ADE80]">people</span>
            <h3 class="font-bold text-sm">Users Report</h3>
        </div>
        <p class="text-xs text-[#94A3B8] mb-4">Export all users with XP, rank, status, and activity metrics.</p>

        <form method="GET" action="{{ route('admin.reports.exportUsers') }}" class="space-y-2 mb-3">
            <input type="date" name="date_from" placeholder="From" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-3 py-2 text-xs">
            <input type="date" name="date_to" placeholder="To" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-3 py-2 text-xs">
            <button type="submit" class="w-full py-2 bg-[#4ADE80] text-[#0A0A0F] rounded-full font-bold text-xs">Export CSV</button>
        </form>
    </div>

    <!-- Submissions Report -->
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-3xl text-[#60A5FA]">fact_check</span>
            <h3 class="font-bold text-sm">Submissions Report</h3>
        </div>
        <p class="text-xs text-[#94A3B8] mb-4">Export all task submissions with status, ratings, and dates.</p>

        <form method="GET" action="{{ route('admin.reports.exportSubmissions') }}" class="space-y-2 mb-3">
            <select name="status" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-3 py-2 text-xs">
                <option value="all">All Status</option>
                <option value="completed">Completed</option>
                <option value="in_progress">In Progress</option>
            </select>
            <input type="date" name="date_from" placeholder="From" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-3 py-2 text-xs">
            <button type="submit" class="w-full py-2 bg-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs">Export CSV</button>
        </form>
    </div>

    <!-- Scenarios Report -->
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-3xl text-purple-400">menu_book</span>
            <h3 class="font-bold text-sm">Scenarios Report</h3>
        </div>
        <p class="text-xs text-[#94A3B8] mb-4">Export all scenarios with categories, levels, and metadata.</p>

        <a href="{{ route('admin.reports.exportScenarios') }}" class="block w-full text-center py-2 bg-purple-500/20 text-purple-400 rounded-full font-bold text-xs mt-3">Export CSV</a>
    </div>

    <!-- Mistakes Report -->
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-icons text-3xl text-red-400">bug_report</span>
            <h3 class="font-bold text-sm">Mistakes Report</h3>
        </div>
        <p class="text-xs text-[#94A3B8] mb-4">Export all user mistakes with questions and correct answers.</p>

        <a href="{{ route('admin.reports.exportMistakes') }}" class="block w-full text-center py-2 bg-red-500/20 text-red-400 rounded-full font-bold text-xs mt-3">Export CSV</a>
    </div>

</div>

<!-- Insights Grid -->
<h2 class="text-lg font-black mb-4 flex items-center gap-2">
    <span class="material-icons text-[#60A5FA]">insights</span>
    Insights
</h2>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Top Users -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">emoji_events</span>
            Top 10 Users
        </h3>
        <div class="space-y-2">
            @foreach($topUsers as $index => $user)
            <div class="flex items-center gap-3 p-2 bg-[#0A0A0F]/50 rounded-xl">
                <span class="text-xs font-bold w-5
                    @if($index == 0) text-yellow-400
                    @elseif($index == 1) text-gray-300
                    @elseif($index == 2) text-amber-600
                    @else text-[#94A3B8] @endif">
                    #{{ $index + 1 }}
                </span>
                <div class="w-7 h-7 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold text-xs">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold truncate">{{ $user->name }}</p>
                </div>
                <span class="text-xs font-bold text-[#4ADE80]">{{ $user->xp_points }} XP</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Failed Scenarios -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-red-400">trending_down</span>
            Most Failed Scenarios
        </h3>
        <div class="space-y-2">
            @forelse($failedScenarios as $fail)
            <div class="flex items-center justify-between p-2 bg-[#0A0A0F]/50 rounded-xl">
                <p class="text-xs font-bold truncate flex-1">{{ $fail->title }}</p>
                <span class="text-xs font-bold text-red-400 ml-2">{{ $fail->fail_count }} fails</span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No data yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Top Tasks -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#60A5FA]">trending_up</span>
            Most Completed Tasks
        </h3>
        <div class="space-y-2">
            @forelse($topTasks as $task)
            <div class="flex items-center justify-between p-2 bg-[#0A0A0F]/50 rounded-xl">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold truncate">{{ $task->title }}</p>
                    <p class="text-[10px] text-[#94A3B8] font-mono">{{ $task->reference_code }}</p>
                </div>
                <span class="text-xs font-bold text-[#4ADE80] ml-2">{{ $task->count }}</span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No data yet.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection