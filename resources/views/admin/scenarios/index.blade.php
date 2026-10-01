@extends('admin.layouts.app')

@section('title', 'Manage Scenarios')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">menu_book</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Scenarios</p>
    </div>
    <div class="card p-6">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">person</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['general'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">General</p>
    </div>
    <div class="card p-6">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">computer</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['it_professional'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">IT Professionals</p>
    </div>
    <div class="card p-6">
        <span class="material-icons text-2xl text-purple-400 mb-2">shield</span>
        <p class="text-3xl font-black text-purple-400">{{ $stats['security_expert'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Security Experts</p>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search..."
            class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
        <select name="user_type" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all">All Types</option>
            <option value="general" {{ $userType == 'general' ? 'selected' : '' }}>General</option>
            <option value="it_professional" {{ $userType == 'it_professional' ? 'selected' : '' }}>IT</option>
            <option value="security_expert" {{ $userType == 'security_expert' ? 'selected' : '' }}>Expert</option>
        </select>
        <select name="level" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all">All Levels</option>
            <option value="easy" {{ $level == 'easy' ? 'selected' : '' }}>Easy</option>
            <option value="medium" {{ $level == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="hard" {{ $level == 'hard' ? 'selected' : '' }}>Hard</option>
            <option value="expert" {{ $level == 'expert' ? 'selected' : '' }}>Expert</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-[#4ADE80] text-[#0A0A0F] rounded-full font-bold text-xs">Search</button>
    </form>

    <a href="{{ route('admin.scenarios.create') }}" class="px-5 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs flex items-center gap-1">
        <span class="material-icons text-sm">add</span> New Scenario
    </a>
</div>

<!-- Delete by Title -->
<div class="card p-4 mb-6">
    <form method="POST" action="{{ route('admin.scenarios.deleteByTitle') }}" class="flex gap-2">
        @csrf
        <input type="text" name="title" placeholder="Delete by exact title..." required
            class="flex-1 bg-[#0A0A0F] border border-red-500/20 rounded-xl px-4 py-2 text-sm">
        <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-full font-bold text-xs">Delete</button>
    </form>
</div>

<!-- Scenarios List -->
<div class="space-y-2">
    @forelse($scenarios as $scenario)
    <div class="card p-4 flex items-center justify-between">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <span class="px-2 py-1 rounded-full text-[10px] font-bold shrink-0
                @if($scenario->user_type == 'general') bg-[#4ADE80]/10 text-[#4ADE80]
                @elseif($scenario->user_type == 'it_professional') bg-[#60A5FA]/10 text-[#60A5FA]
                @else bg-purple-500/10 text-purple-400 @endif">
                {{ strtoupper(str_replace('_', ' ', $scenario->user_type)) }}
            </span>
            <span class="px-2 py-1 rounded-full text-[10px] font-bold shrink-0
                @if($scenario->level == 'easy') bg-[#4ADE80]/10 text-[#4ADE80]
                @elseif($scenario->level == 'medium') bg-yellow-500/10 text-yellow-400
                @elseif($scenario->level == 'hard') bg-orange-500/10 text-orange-400
                @else bg-red-500/10 text-red-400 @endif">
                {{ ucfirst($scenario->level) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-sm truncate">{{ $scenario->title }}</p>
                <p class="text-[10px] text-[#94A3B8]">{{ $scenario->category }} • {{ $scenario->estimated_minutes }} min</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @php
    $pointsMap = ['easy' => 10, 'medium' => 20, 'hard' => 30, 'expert' => 50];
    $scenarioPoints = $scenario->points ?? ($pointsMap[$scenario->level] ?? 10);
@endphp
<span class="text-xs font-bold text-[#4ADE80]">+{{ $scenarioPoints }} XP</span>
            <form method="POST" action="{{ route('admin.scenarios.destroy', $scenario->id) }}" onsubmit="return confirm('Delete this scenario?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400">
                    <span class="material-icons text-lg">delete</span>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#94A3B8] mb-4">menu_book</span>
        <p class="text-[#94A3B8]">No scenarios found.</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $scenarios->appends(request()->query())->links() }}
</div>

@endsection