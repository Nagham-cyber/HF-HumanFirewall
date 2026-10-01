@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">

    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">Top <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Champions</span></h1>
        <p class="text-[#94A3B8] text-sm mt-2">Global leaderboard</p>
    </div>

    <div class="card p-8">
        <div class="space-y-3">
            @foreach($topUsers as $index => $user)
            <div class="flex items-center justify-between p-4 rounded-xl {{ $user->id == Auth::id() ? 'bg-[#4ADE80]/10 border border-[#4ADE80]/30' : 'bg-[#0A0A0F]/50' }}">
                <div class="flex items-center gap-4">
                    <span class="text-2xl font-black
                        @if($index == 0) text-yellow-400
                        @elseif($index == 1) text-gray-300
                        @elseif($index == 2) text-amber-600
                        @else text-[#94A3B8] @endif">
                        #{{ $index + 1 }}
                    </span>
                    <div class="w-10 h-10 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-sm">{{ $user->name }}</p>
                        <p class="text-xs text-[#94A3B8]">{{ $user->security_rank ?? 'Novice' }}</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-[#4ADE80]">{{ $user->xp_points }} XP</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection