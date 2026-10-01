@extends('admin.layouts.app')

@section('title', 'Manage Daily Challenges')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="card p-6 text-center">
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total</p>
    </div>
    <div class="card p-6 text-center">
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['easy'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Easy</p>
    </div>
    <div class="card p-6 text-center">
        <p class="text-3xl font-black text-yellow-400">{{ $stats['medium'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Medium</p>
    </div>
    <div class="card p-6 text-center">
        <p class="text-3xl font-black text-red-400">{{ $stats['hard'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Hard</p>
    </div>
    <div class="card p-6 text-center">
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['today'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Today</p>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search..."
            class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
        <select name="difficulty" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all">All Levels</option>
            <option value="easy" {{ $difficulty == 'easy' ? 'selected' : '' }}>Easy</option>
            <option value="medium" {{ $difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="hard" {{ $difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-[#4ADE80] text-[#0A0A0F] rounded-full font-bold text-xs">Search</button>
    </form>

    <a href="{{ route('admin.challenges.create') }}" class="px-5 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs flex items-center gap-1">
        <span class="material-icons text-sm">add</span> New Challenge
    </a>
</div>

<!-- Challenges List -->
<div class="space-y-2">
    @forelse($challenges as $challenge)
    <div class="card p-4 flex items-center justify-between">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <span class="px-2 py-1 rounded-full text-[10px] font-bold shrink-0
                @if($challenge->difficulty == 'easy') bg-[#4ADE80]/10 text-[#4ADE80]
                @elseif($challenge->difficulty == 'medium') bg-yellow-500/10 text-yellow-400
                @else bg-red-500/10 text-red-400 @endif">
                {{ strtoupper($challenge->difficulty) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-sm truncate">{{ $challenge->question }}</p>
                <p class="text-[10px] text-[#94A3B8]">{{ $challenge->challenge_date }} • +{{ $challenge->points }} XP</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.challenges.destroy', $challenge->id) }}" onsubmit="return confirm('Delete this challenge?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400">
                <span class="material-icons text-lg">delete</span>
            </button>
        </form>
    </div>
    @empty
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#94A3B8] mb-4">bolt</span>
        <p class="text-[#94A3B8]">No challenges found.</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $challenges->appends(request()->query())->links() }}
</div>

@endsection