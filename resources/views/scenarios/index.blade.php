@extends('layouts.app')

@section('title', 'Scenarios')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">Security <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Scenarios</span></h1>
        <p class="text-[#94A3B8] text-sm mt-2">Choose your category and level up</p>
    </div>

    <div class="flex flex-wrap gap-3 mb-8 justify-center">
        <a href="?type=general" class="px-6 py-3 rounded-full font-bold {{ $userType == 'general' ? 'bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] glow-green' : 'glass text-[#94A3B8]' }}">
            <span class="material-icons text-base align-middle mr-1">person</span> General
        </a>
        <a href="?type=it_professional" class="px-6 py-3 rounded-full font-bold {{ $userType == 'it_professional' ? 'bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] glow-green' : 'glass text-[#94A3B8]' }}">
            <span class="material-icons text-base align-middle mr-1">computer</span> IT Pros
        </a>
        <a href="?type=security_expert" class="px-6 py-3 rounded-full font-bold {{ $userType == 'security_expert' ? 'bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] glow-green' : 'glass text-[#94A3B8]' }}">
            <span class="material-icons text-base align-middle mr-1">shield</span> Experts
        </a>
    </div>

    @if(count($scenarios) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($scenarios as $scenario)
        @php $isCompleted = isset($completedIds) && $completedIds->contains($scenario->id); @endphp
        <div class="card group p-6 relative overflow-hidden {{ $isCompleted ? 'opacity-50 hover:opacity-80' : '' }}">
            @if($isCompleted)
            <span class="absolute top-3 right-3 bg-[#4ADE80] text-[#0A0A0F] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1 z-10">
                <span class="material-icons text-sm">check_circle</span> Completed
            </span>
            @endif
            
            <div class="flex items-center justify-between mb-3">
                <span class="px-3 py-1 bg-[#4ADE80]/10 text-[#4ADE80] text-xs font-bold rounded-full">{{ ucfirst($scenario->level) }}</span>
                <span class="text-xs text-[#94A3B8] flex items-center gap-1"><span class="material-icons text-sm">timer</span> {{ $scenario->estimated_minutes }} min</span>
            </div>
            
            <h3 class="text-lg font-bold mb-2 group-hover:text-[#4ADE80]">{{ $scenario->title }}</h3>
            <p class="text-sm text-[#94A3B8] mb-4 line-clamp-2">{{ $scenario->description }}</p>
            
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#4ADE80]">+{{ $scenario->level == 'easy' ? 10 : ($scenario->level == 'medium' ? 20 : ($scenario->level == 'hard' ? 30 : 50)) }} XP</span>
                <a href="/scenarios/{{ $scenario->id }}/play" class="px-4 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm">
                    {{ $isCompleted ? 'Retry' : 'Start' }}
                    <span class="material-icons text-sm align-middle">{{ $isCompleted ? 'refresh' : 'arrow_forward' }}</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card p-16 text-center">
        <span class="material-icons text-5xl text-[#4ADE80] mb-4">menu_book</span>
        <h2 class="text-xl font-bold">No scenarios yet</h2>
    </div>
    @endif
</div>
@endsection