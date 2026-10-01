@extends('layouts.app')

@section('title', 'Badges')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">Achievement <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Badges</span></h1>
    </div>

    @php $categories = ['general' => ['label' => 'General', 'icon' => 'person', 'color' => 'text-[#4ADE80]'], 'it_professional' => ['label' => 'IT Professional', 'icon' => 'computer', 'color' => 'text-[#60A5FA]'], 'security_expert' => ['label' => 'Security Expert', 'icon' => 'shield', 'color' => 'text-purple-400']]; @endphp

    @foreach($categories as $catType => $catInfo)
    <div class="mb-14">
        <div class="flex items-center gap-3 mb-6">
            <span class="material-icons text-2xl {{ $catInfo['color'] }}">{{ $catInfo['icon'] }}</span>
            <h2 class="text-xl font-black">{{ $catInfo['label'] }}</h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($badges->where('category', $catType) as $badge)
            @php $isEarned = $earnedBadgeIds->contains($badge->id); $progress = $categoryProgress[$catType][$badge->level] ?? ['completed' => 0, 'total' => 0, 'percentage' => 0]; @endphp
            <div class="card p-6 text-center relative {{ $isEarned ? 'glow-green' : 'opacity-60' }}">
                <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 bg-gradient-to-br {{ $badge->color }}">
                    <span class="material-icons text-4xl text-[#0A0A0F]">{{ $badge->icon }}</span>
                </div>
                <h3 class="font-bold text-xs mb-1">{{ $badge->name }}</h3>
                <p class="text-sm font-black @if($badge->tier == 'bronze') text-amber-400 @elseif($badge->tier == 'silver') text-gray-300 @elseif($badge->tier == 'gold') text-yellow-400 @else text-purple-400 @endif">
                    {{ $progress['completed'] }}/{{ $progress['total'] }}
                </p>
                <div class="w-full h-1 bg-[#4ADE80]/10 rounded-full mt-2 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] rounded-full" style="width: {{ $progress['percentage'] }}%"></div>
                </div>
                <span class="absolute top-3 right-3 material-icons {{ $isEarned ? 'text-[#4ADE80]' : 'text-[#94A3B8]/40' }}">{{ $isEarned ? 'verified' : 'lock' }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection