@extends('admin.layouts.app')

@section('title', 'Team Submissions')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">assignment_turned_in</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Submissions</p>
    </div>
    <div class="card p-6">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">check_circle</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['completed'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Completed</p>
    </div>
    <div class="card p-6">
        <span class="material-icons text-2xl text-orange-400 mb-2">pending</span>
        <p class="text-3xl font-black text-orange-400">{{ $stats['in_progress'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">In Progress</p>
    </div>
    <div class="card p-6">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">people</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['unique_users'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Active Users</p>
    </div>
</div>

<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by user, task, or reference..."
            class="flex-1 min-w-[200px] bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
        
        <select name="status" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
        </select>

        <select name="section" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all">All Sections</option>
            <option value="network_defense" {{ $section == 'network_defense' ? 'selected' : '' }}>Network</option>
            <option value="dfir" {{ $section == 'dfir' ? 'selected' : '' }}>DFIR</option>
            <option value="pentest" {{ $section == 'pentest' ? 'selected' : '' }}>Pentest</option>
            <option value="devsecops" {{ $section == 'devsecops' ? 'selected' : '' }}>DevSecOps</option>
            <option value="grc" {{ $section == 'grc' ? 'selected' : '' }}>GRC</option>
            <option value="automation" {{ $section == 'automation' ? 'selected' : '' }}>Automation</option>
        </select>

        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm">
            <span class="material-icons text-sm align-middle">search</span> Filter
        </button>
    </form>
</div>

<!-- Submissions List -->
<div class="space-y-3">
    @forelse($submissions as $sub)
    <a href="{{ route('admin.tasks.submission.show', $sub->id) }}" class="card p-4 flex items-center justify-between hover:scale-[1.01] block">
        <div class="flex items-center gap-4 flex-1 min-w-0">
            <div class="w-10 h-10 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold shrink-0">
                {{ strtoupper(substr($sub->user_name, 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="font-bold text-sm">{{ $sub->user_name }}</p>
                    <span class="text-[10px] text-[#94A3B8]">{{ $sub->user_email }}</span>
                </div>
                <p class="text-xs text-[#94A3B8] mt-1 truncate">
                    <span class="text-[#60A5FA] font-mono">{{ $sub->reference_code }}</span> · {{ $sub->task_title }}
                </p>
            </div>

            <span class="px-2 py-1 rounded-full text-[10px] font-bold shrink-0
                @if($sub->section == 'network_defense') bg-[#4ADE80]/10 text-[#4ADE80]
                @elseif($sub->section == 'dfir') bg-red-500/10 text-red-400
                @elseif($sub->section == 'pentest') bg-purple-500/10 text-purple-400
                @elseif($sub->section == 'devsecops') bg-[#60A5FA]/10 text-[#60A5FA]
                @elseif($sub->section == 'grc') bg-yellow-500/10 text-yellow-400
                @else bg-orange-500/10 text-orange-400 @endif">
                {{ strtoupper(str_replace('_', ' ', $sub->section)) }}
            </span>
        </div>

        <div class="flex items-center gap-4 ml-4 shrink-0">
            @if($sub->difficulty_rating)
            <span class="text-xs text-[#94A3B8]">Diff: {{ $sub->difficulty_rating }}/5</span>
            @endif

            <span class="px-3 py-1 rounded-full text-[10px] font-bold
                {{ $sub->status == 'completed' ? 'bg-[#4ADE80]/10 text-[#4ADE80]' : 'bg-orange-500/10 text-orange-400' }}">
                {{ ucfirst($sub->status) }}
            </span>

            <span class="material-icons text-[#94A3B8]">arrow_forward</span>
        </div>
    </a>
    @empty
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#94A3B8] mb-4">inbox</span>
        <h3 class="text-xl font-black mb-2">No Submissions Yet</h3>
        <p class="text-[#94A3B8] text-sm">Team members haven't submitted any task reports.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $submissions->appends(request()->query())->links() }}
</div>

@endsection