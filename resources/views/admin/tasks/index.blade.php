@extends('admin.layouts.app')

@section('title', 'Cyber Tasks')

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Total</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-[#4ADE80]">{{ $stats['network_defense'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Network</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-red-400">{{ $stats['dfir'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">DFIR</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-purple-400">{{ $stats['pentest'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Pentest</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-[#60A5FA]">{{ $stats['devsecops'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">DevSecOps</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-yellow-400">{{ $stats['grc'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">GRC</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-orange-400">{{ $stats['automation'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Automation</p>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 rounded-full text-xs font-bold {{ !$section ? 'bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F]' : 'glass text-[#94A3B8]' }}">All</a>
        <a href="?section=network_defense" class="px-4 py-2 rounded-full text-xs font-bold {{ $section == 'network_defense' ? 'bg-[#4ADE80]/20 text-[#4ADE80]' : 'glass text-[#94A3B8]' }}">Network</a>
        <a href="?section=dfir" class="px-4 py-2 rounded-full text-xs font-bold {{ $section == 'dfir' ? 'bg-red-500/20 text-red-400' : 'glass text-[#94A3B8]' }}">DFIR</a>
        <a href="?section=pentest" class="px-4 py-2 rounded-full text-xs font-bold {{ $section == 'pentest' ? 'bg-purple-500/20 text-purple-400' : 'glass text-[#94A3B8]' }}">Pentest</a>
        <a href="?section=devsecops" class="px-4 py-2 rounded-full text-xs font-bold {{ $section == 'devsecops' ? 'bg-[#60A5FA]/20 text-[#60A5FA]' : 'glass text-[#94A3B8]' }}">DevSecOps</a>
        <a href="?section=grc" class="px-4 py-2 rounded-full text-xs font-bold {{ $section == 'grc' ? 'bg-yellow-500/20 text-yellow-400' : 'glass text-[#94A3B8]' }}">GRC</a>
        <a href="?section=automation" class="px-4 py-2 rounded-full text-xs font-bold {{ $section == 'automation' ? 'bg-orange-500/20 text-orange-400' : 'glass text-[#94A3B8]' }}">Automation</a>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('admin.tasks.submissions') }}" class="px-4 py-2 bg-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs flex items-center gap-1">
            <span class="material-icons text-sm">visibility</span> View Submissions
        </a>
        <a href="{{ route('admin.tasks.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs flex items-center gap-1">
            <span class="material-icons text-sm">add</span> New Task
        </a>
    </div>
</div>

<!-- Search -->
<form method="GET" class="card p-4 mb-6">
    <div class="flex gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search tasks by title or reference code..."
            class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm">
            Search
        </button>
    </div>
</form>

<!-- Tasks List -->
<div class="space-y-2">
    @forelse($tasks as $task)
    <div class="card p-4 flex items-center justify-between hover:scale-[1.01]">
        <div class="flex items-center gap-4 flex-1 min-w-0">
            <span class="px-2 py-1 rounded-full text-[10px] font-bold shrink-0
                @if($task->section == 'network_defense') bg-[#4ADE80]/10 text-[#4ADE80]
                @elseif($task->section == 'dfir') bg-red-500/10 text-red-400
                @elseif($task->section == 'pentest') bg-purple-500/10 text-purple-400
                @elseif($task->section == 'devsecops') bg-[#60A5FA]/10 text-[#60A5FA]
                @elseif($task->section == 'grc') bg-yellow-500/10 text-yellow-400
                @else bg-orange-500/10 text-orange-400 @endif">
                {{ strtoupper(str_replace('_', ' ', $task->section)) }}
            </span>

            <div class="min-w-0 flex-1">
                <p class="text-[10px] text-[#94A3B8] font-mono">{{ $task->reference_code }}</p>
                <p class="font-bold text-sm truncate">{{ $task->title }}</p>
            </div>

            <div class="flex items-center gap-4 text-xs shrink-0">
                <span class="text-[#94A3B8]">{{ $task->estimated_time }}</span>
                <span class="text-orange-400 font-bold">Difficulty {{ $task->difficulty }}/5</span>
                <span class="text-[#4ADE80] font-bold">+{{ $task->points }} XP</span>
            </div>
        </div>

        <div class="flex items-center gap-2 ml-4 shrink-0">
            <a href="/tasks/{{ $task->id }}" target="_blank" class="p-2 rounded-lg hover:bg-[#4ADE80]/10 text-[#4ADE80]" title="Preview">
                <span class="material-icons text-lg">visibility</span>
            </a>
            <form method="POST" action="{{ route('admin.tasks.destroy', $task->id) }}" onsubmit="return confirm('Delete this task?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400" title="Delete">
                    <span class="material-icons text-lg">delete</span>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#4ADE80] mb-4">assignment</span>
        <p class="text-[#94A3B8]">No tasks found.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $tasks->appends(request()->query())->links() }}
</div>

@endsection