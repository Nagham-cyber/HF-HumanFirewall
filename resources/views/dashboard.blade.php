@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">

    <!-- Hero -->
    <div class="text-center mb-14">
        <h1 class="text-4xl font-black mb-2">Welcome back, <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">{{ Auth::user()->name }}</span></h1>
        <div class="flex items-center justify-center gap-2 mt-3">
            <span class="w-2 h-2 bg-[#4ADE80] rounded-full animate-pulse"></span>
            <p class="text-sm text-[#94A3B8]">Cyber Defense Active</p>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="card p-8 mb-10">
        <h3 class="font-bold text-sm text-[#94A3B8] uppercase tracking-widest mb-6">Your Progress</h3>
        <div class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <span class="material-icons text-2xl text-[#4ADE80]">stars</span>
                <p class="text-2xl font-black text-[#4ADE80] mt-2">{{ $stats['total_points'] ?? 0 }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">XP</p>
            </div>
            <div class="text-center">
                <span class="material-icons text-2xl text-[#60A5FA]">shield</span>
                <p class="text-xs font-black text-[#60A5FA] mt-3">{{ $stats['security_level'] ?? 'Novice' }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Level</p>
            </div>
            <div class="text-center">
                <span class="material-icons text-2xl text-orange-400">local_fire_department</span>
                <p class="text-2xl font-black text-orange-400 mt-2">{{ $stats['streak_days'] ?? 0 }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Days</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap justify-center gap-3 mb-10">
        <a href="{{ route('scenarios.index') }}" class="card px-5 py-3 flex items-center gap-2 hover:scale-105 transition-all">
            <span class="material-icons text-lg text-[#4ADE80]">security</span>
            <span class="text-xs font-bold">Scenarios</span>
        </a>
        <a href="{{ route('daily-challenge.index') }}" class="card px-5 py-3 flex items-center gap-2 hover:scale-105 transition-all">
            <span class="material-icons text-lg text-orange-400">bolt</span>
            <span class="text-xs font-bold">Daily</span>
        </a>
        <a href="{{ route('mistakes.index') }}" class="card px-5 py-3 flex items-center gap-2 hover:scale-105 transition-all">
            <span class="material-icons text-lg text-red-400">bug_report</span>
            <span class="text-xs font-bold">Mistakes</span>
        </a>
        <a href="{{ route('badges.index') }}" class="card px-5 py-3 flex items-center gap-2 hover:scale-105 transition-all">
            <span class="material-icons text-lg text-[#4ADE80]">military_tech</span>
            <span class="text-xs font-bold">Badges</span>
        </a>
        <a href="{{ route('progress') }}" class="card px-5 py-3 flex items-center gap-2 hover:scale-105 transition-all">
            <span class="material-icons text-lg text-[#4ADE80]">trending_up</span>
            <span class="text-xs font-bold">Progress</span>
        </a>
        <a href="{{ route('ai.assistant') }}" class="card px-5 py-3 flex items-center gap-2 hover:scale-105 transition-all">
            <span class="material-icons text-lg text-[#60A5FA]">smart_toy</span>
            <span class="text-xs font-bold">AI Assistant</span>
        </a>
    </div>

    <!-- Leaderboard -->
    <div class="card p-6 max-w-md mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-sm flex items-center gap-2">
                <span class="material-icons text-[#4ADE80] text-lg">emoji_events</span>
                Top Champions
            </h3>
            <a href="{{ route('leaderboard') }}" class="text-xs text-[#4ADE80] hover:text-[#60A5FA]">View All</a>
        </div>
        <div class="space-y-2">
            @forelse($topUsers ?? [] as $index => $topUser)
            <div class="flex items-center justify-between p-2 rounded-xl bg-[#0A0A0F]/50 {{ $topUser->id == Auth::id() ? 'border border-[#4ADE80]/30' : '' }}">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold text-xs">
                        {{ substr($topUser->name, 0, 1) }}
                    </div>
                    <span class="text-xs">{{ $topUser->name }}</span>
                </div>
                <span class="text-xs font-bold text-[#4ADE80]">{{ $topUser->xp_points }} XP</span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8]">No data yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection