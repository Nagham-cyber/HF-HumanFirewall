@extends('layouts.app')

@section('title', 'Cyber Tasks')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">

    <!-- Hero -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">Cyber <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Tasks</span></h1>
        <p class="text-[#94A3B8] text-sm mt-2">Professional security tasks for your team</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-8 max-w-2xl mx-auto">
        <div class="card p-5 text-center">
            <span class="material-icons text-2xl text-[#4ADE80] mb-2">assignment</span>
            <p class="text-2xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
            <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Tasks</p>
        </div>
        <div class="card p-5 text-center">
            <span class="material-icons text-2xl text-[#60A5FA] mb-2">check_circle</span>
            <p class="text-2xl font-black text-[#60A5FA]">{{ $stats['completed'] }}</p>
            <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Completed</p>
        </div>
        <div class="card p-5 text-center">
            <span class="material-icons text-2xl text-orange-400 mb-2">pending</span>
            <p class="text-2xl font-black text-orange-400">{{ $stats['in_progress'] }}</p>
            <p class="text-[10px] text-[#94A3B8] uppercase mt-1">In Progress</p>
        </div>
    </div>

    <!-- Section Filter -->
    <div class="flex flex-wrap gap-3 mb-8 justify-center">
        @php
            $sections = [
                'all' => 'All Tasks',
                'network_defense' => 'Network',
                'dfir' => 'DFIR',
                'pentest' => 'Pentest',
                'devsecops' => 'DevSecOps',
                'grc' => 'GRC',
                'automation' => 'Automation',
            ];
        @endphp

        @foreach($sections as $key => $label)
        <a href="?section={{ $key }}" class="px-5 py-2 rounded-full font-bold text-sm transition-all
            {{ ($section ?? 'all') == $key ? 'bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] glow-green' : 'glass text-[#94A3B8] hover:text-[#4ADE80]' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <!-- Tasks Grid -->
    @if(count($tasks) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($tasks as $task)
        @php $isCompleted = in_array($task->id, $submittedTaskIds); @endphp
        <a href="/tasks/{{ $task->id }}" class="card p-6 group hover:scale-105 transition-all {{ $isCompleted ? 'opacity-70' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="px-2 py-1 rounded-full text-[10px] font-bold
                    @if($task->section == 'network_defense') bg-[#4ADE80]/10 text-[#4ADE80]
                    @elseif($task->section == 'dfir') bg-red-500/10 text-red-400
                    @elseif($task->section == 'pentest') bg-purple-500/10 text-purple-400
                    @elseif($task->section == 'devsecops') bg-[#60A5FA]/10 text-[#60A5FA]
                    @elseif($task->section == 'grc') bg-yellow-500/10 text-yellow-400
                    @else bg-orange-500/10 text-orange-400 @endif">
                    {{ strtoupper(str_replace('_', ' ', $task->section)) }}
                </span>
                @if($isCompleted)
                <span class="material-icons text-[#4ADE80]">check_circle</span>
                @endif
            </div>

            <p class="text-[10px] text-[#94A3B8] mb-1 font-mono">{{ $task->reference_code }}</p>
            <h3 class="font-bold text-sm mb-2 group-hover:text-[#4ADE80] transition-colors">{{ $task->title }}</h3>
            <p class="text-xs text-[#94A3B8] line-clamp-2 mb-4">{{ Str::limit($task->description, 80) }}</p>

            <div class="flex items-center justify-between text-[10px] text-[#94A3B8]">
                <span class="flex items-center gap-1">
                    <span class="material-icons text-xs">schedule</span> {{ $task->estimated_time }}
                </span>
                <span class="flex items-center gap-1 text-[#4ADE80] font-bold">
                    <span class="material-icons text-xs">stars</span> +{{ $task->points }} XP
                </span>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#4ADE80] mb-4">assignment</span>
        <h2 class="text-xl font-black mb-2">No Tasks Found</h2>
        <p class="text-[#94A3B8]">No tasks available in this section.</p>
    </div>
    @endif

</div>
@endsection