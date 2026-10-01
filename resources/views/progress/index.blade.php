@extends('layouts.app')

@section('title', 'Progress')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">My <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Progress</span></h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        <div class="card p-6 text-center"><span class="material-icons text-2xl text-[#4ADE80] mb-2">stars</span><p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total_points'] }}</p><p class="text-[10px] text-[#94A3B8] uppercase">XP</p></div>
        <div class="card p-6 text-center"><span class="material-icons text-2xl text-[#60A5FA] mb-2">task_alt</span><p class="text-3xl font-black text-[#60A5FA]">{{ $stats['completed'] }}/{{ $stats['total_scenarios'] }}</p><p class="text-[10px] text-[#94A3B8] uppercase">Scenarios</p></div>
        <div class="card p-6 text-center"><span class="material-icons text-2xl text-[#4ADE80] mb-2">check_circle</span><p class="text-3xl font-black text-[#4ADE80]">{{ $stats['correct_answers'] }}</p><p class="text-[10px] text-[#94A3B8] uppercase">Correct</p></div>
        <div class="card p-6 text-center"><span class="material-icons text-2xl text-red-400 mb-2">cancel</span><p class="text-3xl font-black text-red-400">{{ $stats['wrong_answers'] }}</p><p class="text-[10px] text-[#94A3B8] uppercase">Wrong</p></div>
    </div>

    @php $catInfo = ['general' => ['label' => 'General', 'icon' => 'person', 'color' => 'text-[#4ADE80]'], 'it_professional' => ['label' => 'IT Professional', 'icon' => 'computer', 'color' => 'text-[#60A5FA]'], 'security_expert' => ['label' => 'Security Expert', 'icon' => 'shield', 'color' => 'text-purple-400']]; @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($catInfo as $catType => $cat)
        <div class="card p-8">
            <div class="flex items-center gap-3 mb-6">
                <span class="material-icons text-2xl {{ $cat['color'] }}">{{ $cat['icon'] }}</span>
                <h3 class="font-bold">{{ $cat['label'] }}</h3>
            </div>
            @foreach(['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard', 'expert' => 'Expert'] as $levelType => $levelLabel)
            @php $p = $categoryStats[$catType][$levelType] ?? ['completed' => 0, 'total' => 0, 'percentage' => 0]; @endphp
            <div class="mb-4">
                <div class="flex justify-between mb-2"><span class="text-xs">{{ $levelLabel }}</span><span class="text-xs font-bold text-[#94A3B8]">{{ $p['completed'] }}/{{ $p['total'] }}</span></div>
                <div class="w-full h-1 bg-[#4ADE80]/10 rounded-full"><div class="h-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] rounded-full" style="width: {{ $p['percentage'] }}%"></div></div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
@endsection