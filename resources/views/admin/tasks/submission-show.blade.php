@extends('admin.layouts.app')

@section('title', 'Submission Review')

@section('content')

<!-- Back Button -->
<a href="{{ route('admin.tasks.submissions') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Submissions
</a>

<!-- User Info Card -->
<div class="card p-6 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-2xl">
                {{ strtoupper(substr($submission->user_name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-black">{{ $submission->user_name }}</h2>
                <p class="text-xs text-[#94A3B8]">{{ $submission->user_email }}</p>
                <p class="text-xs text-[#4ADE80] mt-1">{{ $submission->user_rank ?? 'Novice' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-center">
                <p class="text-2xl font-black text-[#4ADE80]">{{ $userStats['xp'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase">Total XP</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-black text-[#60A5FA]">{{ $userStats['completed'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase">Completed</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-black text-orange-400">{{ $userStats['total_submissions'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase">Submissions</p>
            </div>
        </div>
    </div>
</div>

<!-- Task Info -->
<div class="card p-6 mb-6">
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <span class="px-3 py-1 rounded-full text-xs font-bold
            @if($submission->section == 'network_defense') bg-[#4ADE80]/10 text-[#4ADE80]
            @elseif($submission->section == 'dfir') bg-red-500/10 text-red-400
            @elseif($submission->section == 'pentest') bg-purple-500/10 text-purple-400
            @elseif($submission->section == 'devsecops') bg-[#60A5FA]/10 text-[#60A5FA]
            @elseif($submission->section == 'grc') bg-yellow-500/10 text-yellow-400
            @else bg-orange-500/10 text-orange-400 @endif">
            {{ strtoupper(str_replace('_', ' ', $submission->section)) }}
        </span>
        <span class="text-xs text-[#94A3B8] font-mono">{{ $submission->reference_code }}</span>
    </div>

    <h1 class="text-2xl font-black mb-2">{{ $submission->title }}</h1>
    <p class="text-sm text-[#94A3B8] mb-4">{{ $submission->description }}</p>

    <div class="flex flex-wrap gap-4 text-xs">
        <span class="text-[#94A3B8]"><span class="material-icons text-sm align-middle">schedule</span> {{ $submission->estimated_time }}</span>
        <span class="text-orange-400 font-bold">Difficulty {{ $submission->difficulty }}/5</span>
        @php
    $taskPoints = $submission->points ?? ($submission->task_points ?? 100);
@endphp
<span class="text-[#4ADE80] font-bold">+{{ $taskPoints }} XP</span>
        <span class="px-3 py-1 rounded-full text-[10px] font-bold
            {{ $submission->status == 'completed' ? 'bg-[#4ADE80]/10 text-[#4ADE80]' : 'bg-orange-500/10 text-orange-400' }}">
            {{ ucfirst($submission->status) }}
        </span>
    </div>
</div>
 <!-- Review Status -->
@if($submission->admin_reviewed)
<div class="card p-6 mb-6 {{ $submission->admin_approved ? 'border-l-4 border-[#4ADE80]' : 'border-l-4 border-red-500' }}">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-sm {{ $submission->admin_approved ? 'text-[#4ADE80]' : 'text-red-400' }} uppercase flex items-center gap-2">
            <span class="material-icons text-base">{{ $submission->admin_approved ? 'check_circle' : 'cancel' }}</span>
            {{ $submission->admin_approved ? 'Approved' : 'Rejected' }}
        </h3>
        <span class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($submission->reviewed_at)->diffForHumans() }}</span>
    </div>
    @if($submission->admin_feedback)
    <p class="text-sm text-[#F1F5F9] leading-relaxed whitespace-pre-wrap">{{ $submission->admin_feedback }}</p>
    @endif
</div>
@endif

<!-- Review Actions -->
@if(!$submission->admin_reviewed || !$submission->admin_approved)
<div class="card p-6 mb-6">
    <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-4 flex items-center gap-2">
        <span class="material-icons text-base">rate_review</span>
        Admin Review
    </h3>

    <form method="POST" action="{{ route('admin.tasks.submission.review', $submission->submission_id) }}" class="space-y-4">
        @csrf

        <div>
            <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Feedback / Notes (Optional)</label>
            <textarea name="feedback" rows="4" placeholder="Write feedback for the user..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ $submission->admin_feedback ?? '' }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <button type="submit" name="action" value="approve"
                class="py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-sm hover:scale-105 transition-all flex items-center justify-center gap-2">
                <span class="material-icons">check_circle</span>
                Approve +{{ $taskPoints }} XP
            </button>
            <button type="submit" name="action" value="reject"
                class="py-3 bg-red-500/20 text-red-400 rounded-full font-black text-sm hover:bg-red-500/30 transition-all flex items-center justify-center gap-2">
                <span class="material-icons">cancel</span>
                Reject (No XP)
            </button>
        </div>
    </form>
</div>
@endif 

<!-- User's Responses -->
<div class="space-y-4 mb-6">

    <div class="card p-6 border-l-4 border-[#4ADE80]">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-3 flex items-center gap-2">
            <span class="material-icons text-base">looks_one</span>
            Step by Step
        </h3>
        <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $submission->step_by_step ?: '— Not provided —' }}</p>
    </div>

    <div class="card p-6 border-l-4 border-[#60A5FA]">
        <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-3 flex items-center gap-2">
            <span class="material-icons text-base">looks_two</span>
            Observations
        </h3>
        <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $submission->observations ?: '— Not provided —' }}</p>
    </div>

    <div class="card p-6 border-l-4 border-purple-400">
        <h3 class="font-bold text-sm text-purple-400 uppercase mb-3 flex items-center gap-2">
            <span class="material-icons text-base">looks_3</span>
            Lessons Learned
        </h3>
        <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $submission->lessons_learned ?: '— Not provided —' }}</p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="card p-6">
            <h3 class="font-bold text-xs text-[#94A3B8] uppercase mb-2">Difficulty Rating</h3>
            <p class="text-2xl font-black text-orange-400">{{ $submission->difficulty_rating ?: '—' }}/5</p>
        </div>
        <div class="card p-6">
            <h3 class="font-bold text-xs text-[#94A3B8] uppercase mb-2">Submitted</h3>
            <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y') }}</p>
            <p class="text-xs text-[#94A3B8]">{{ \Carbon\Carbon::parse($submission->submitted_at)->diffForHumans() }}</p>
        </div>
    </div>

</div>

@endsection