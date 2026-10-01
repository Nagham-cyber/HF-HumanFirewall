@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">

    <!-- Back Button -->
    <a href="/tasks" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA] transition-all">
        <span class="material-icons text-sm">arrow_back</span> Back to Tasks
    </a>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-[#4ADE80]/10 border border-[#4ADE80]/30 rounded-xl p-4 mb-6 text-[#4ADE80] text-sm flex items-center gap-2">
        <span class="material-icons">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 text-red-400 text-sm">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-icons">error</span>
            <strong>Please fix the following errors:</strong>
        </div>
        <ul class="list-disc list-inside text-xs">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- ==================== SUBMISSION STATUS ==================== -->
    @if($submission)
    <div class="card p-6 mb-6
        @if($submission->admin_reviewed && $submission->admin_approved) border-l-4 border-[#4ADE80]
        @elseif($submission->admin_reviewed && !$submission->admin_approved) border-l-4 border-red-500
        @else border-l-4 border-orange-400 @endif">

        <div class="flex items-center gap-3 mb-3">
            @if($submission->admin_reviewed && $submission->admin_approved)
            <span class="material-icons text-3xl text-[#4ADE80]">check_circle</span>
            <div>
                <h3 class="font-black text-[#4ADE80]">Approved! +{{ $task->points }} XP earned</h3>
                <p class="text-[10px] text-[#94A3B8]">Reviewed {{ \Carbon\Carbon::parse($submission->reviewed_at)->diffForHumans() }}</p>
            </div>
            @elseif($submission->admin_reviewed && !$submission->admin_approved)
            <span class="material-icons text-3xl text-red-400">cancel</span>
            <div>
                <h3 class="font-black text-red-400">Needs Revision</h3>
                <p class="text-[10px] text-[#94A3B8]">Reviewed {{ \Carbon\Carbon::parse($submission->reviewed_at)->diffForHumans() }}</p>
            </div>
            @else
            <span class="material-icons text-3xl text-orange-400">pending</span>
            <div>
                <h3 class="font-black text-orange-400">Pending Admin Review</h3>
                <p class="text-[10px] text-[#94A3B8]">Submitted {{ \Carbon\Carbon::parse($submission->submitted_at)->diffForHumans() }}</p>
            </div>
            @endif
        </div>

        <p class="text-xs text-[#94A3B8] mb-3">
            @if($submission->admin_reviewed && $submission->admin_approved)
            Your submission was reviewed and approved. Great work!
            @elseif($submission->admin_reviewed && !$submission->admin_approved)
            Your submission needs revision. Please review the admin's feedback below and resubmit.
            @else
            Your submission is being reviewed by an admin. You will receive <span class="text-[#4ADE80] font-bold">+{{ $task->points }} XP</span> once approved.
            @endif
        </p>

        @if($submission->admin_feedback)
        <div class="bg-[#0A0A0F]/50 rounded-xl p-4 mt-3 border border-[#60A5FA]/20">
            <p class="text-xs text-[#60A5FA] uppercase font-bold mb-2 flex items-center gap-2">
                <span class="material-icons text-sm">chat_bubble</span> Admin Feedback
            </p>
            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $submission->admin_feedback }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- ==================== TASK HEADER ==================== -->
    <div class="card p-8 mb-6">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold
                @if($task->section == 'network_defense') bg-[#4ADE80]/10 text-[#4ADE80]
                @elseif($task->section == 'dfir') bg-red-500/10 text-red-400
                @elseif($task->section == 'pentest') bg-purple-500/10 text-purple-400
                @elseif($task->section == 'devsecops') bg-[#60A5FA]/10 text-[#60A5FA]
                @elseif($task->section == 'grc') bg-yellow-500/10 text-yellow-400
                @else bg-orange-500/10 text-orange-400 @endif">
                {{ strtoupper(str_replace('_', ' ', $task->section)) }}
            </span>
            <span class="text-xs text-[#94A3B8] font-mono">{{ $task->reference_code }}</span>
        </div>

        <h1 class="text-3xl font-black mb-4">{{ $task->title }}</h1>
        <p class="text-[#94A3B8] leading-relaxed mb-6">{{ $task->description }}</p>

        <div class="flex flex-wrap gap-4 text-xs">
            <span class="flex items-center gap-1 text-[#94A3B8]">
                <span class="material-icons text-sm">schedule</span> {{ $task->estimated_time }}
            </span>
            <span class="flex items-center gap-1 text-[#94A3B8]">
                <span class="material-icons text-sm">signal_cellular_alt</span> Difficulty: {{ $task->difficulty }}/5
            </span>
            <span class="flex items-center gap-1 text-[#4ADE80] font-bold">
                <span class="material-icons text-sm">stars</span> +{{ $task->points }} XP
            </span>
        </div>
    </div>

    <!-- ==================== OVERVIEW ==================== -->
    @if($task->overview)
    <div class="card p-8 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">visibility</span> Overview
        </h3>
        <p class="leading-relaxed text-[#F1F5F9]">{{ $task->overview }}</p>
    </div>
    @endif

    <!-- ==================== OBJECTIVES ==================== -->
    @if($task->objectives)
    <div class="card p-8 mb-6">
        <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">flag</span> Learning Objectives
        </h3>
        @php $objectives = is_string($task->objectives) ? json_decode($task->objectives, true) : $task->objectives; @endphp
        <ul class="space-y-3">
            @foreach($objectives ?? [] as $objective)
            <li class="flex items-start gap-3 text-[#F1F5F9] text-sm">
                <span class="material-icons text-[#4ADE80] text-base mt-0.5">check_circle_outline</span>
                <span>{{ $objective }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- ==================== INSTRUCTIONS ==================== -->
    @if($task->instructions)
    <div class="card p-8 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">list_alt</span> Step-by-Step Instructions
        </h3>
        @php $instructions = is_string($task->instructions) ? json_decode($task->instructions, true) : $task->instructions; @endphp
        <ol class="space-y-3">
            @foreach($instructions ?? [] as $index => $instruction)
            <li class="flex items-start gap-3 text-[#F1F5F9] text-sm">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#4ADE80]/10 text-[#4ADE80] flex items-center justify-center text-xs font-bold">{{ $index + 1 }}</span>
                <span class="mt-0.5">{{ $instruction }}</span>
            </li>
            @endforeach
        </ol>
    </div>
    @endif

    <!-- ==================== TOOLS & RESOURCES ==================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <!-- Tools -->
        @if($task->tools_list)
        <div class="card p-8">
            <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
                <span class="material-icons text-base">build</span> Tools Required
            </h3>
            @php $tools = is_string($task->tools_list) ? json_decode($task->tools_list, true) : $task->tools_list; @endphp
            <ul class="space-y-2">
                @foreach($tools ?? [] as $tool)
                @php
                    preg_match('/https?:\/\/[^\s]+/', $tool, $matches);
                    $url = $matches[0] ?? null;
                    $text = trim(str_replace($url, '', $tool));
                    $text = rtrim($text, ' -•·');
                @endphp
                <li class="text-xs text-[#94A3B8] flex items-start gap-2">
                    <span class="material-icons text-[#4ADE80] text-sm mt-0.5">arrow_right</span>
                    @if($url)
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                        class="text-[#4ADE80] hover:text-[#60A5FA] hover:underline transition-colors flex items-center gap-1">
                        {{ $text ?: $url }}
                        <span class="material-icons text-xs">open_in_new</span>
                    </a>
                    @else
                    <span>{{ $tool }}</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Resources -->
        @if($task->resources)
        <div class="card p-8">
            <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-4 flex items-center gap-2">
                <span class="material-icons text-base">menu_book</span> Resources
            </h3>
            @php $resources = is_string($task->resources) ? json_decode($task->resources, true) : $task->resources; @endphp
            <ul class="space-y-2">
                @foreach($resources ?? [] as $resource)
                @php
                    preg_match('/https?:\/\/[^\s]+/', $resource, $matches);
                    $url = $matches[0] ?? null;
                    $text = trim(str_replace($url, '', $resource));
                    $text = rtrim($text, ' -•·');
                @endphp
                <li class="text-xs text-[#94A3B8] flex items-start gap-2">
                    <span class="material-icons text-[#60A5FA] text-sm mt-0.5">link</span>
                    @if($url)
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                        class="text-[#60A5FA] hover:text-[#4ADE80] hover:underline transition-colors flex items-center gap-1">
                        {{ $text ?: $url }}
                        <span class="material-icons text-xs">open_in_new</span>
                    </a>
                    @else
                    <span>{{ $resource }}</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>

    <!-- ==================== DELIVERABLES & HINTS ==================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <!-- Deliverables -->
        @if($task->deliverables)
        <div class="card p-8">
            <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
                <span class="material-icons text-base">assignment_turned_in</span> Deliverables
            </h3>
            @php $deliverables = is_string($task->deliverables) ? json_decode($task->deliverables, true) : $task->deliverables; @endphp
            <ul class="space-y-2">
                @foreach($deliverables ?? [] as $deliverable)
                <li class="text-xs text-[#94A3B8] flex items-start gap-2">
                    <span class="material-icons text-[#4ADE80] text-sm mt-0.5">check</span>
                    <span>{{ $deliverable }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Hints -->
        @if($task->hints)
        <div class="card p-8 border-yellow-500/20">
            <h3 class="font-bold text-sm text-yellow-400 uppercase mb-4 flex items-center gap-2">
                <span class="material-icons text-base">lightbulb</span> Pro Tip
            </h3>
            <p class="text-xs text-[#94A3B8] leading-relaxed italic">{{ $task->hints }}</p>
        </div>
        @endif

    </div>

    <!-- ==================== SUBMISSION FORM ==================== -->
    <div class="card p-8">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-icons text-[#4ADE80]">edit_note</span>
                Task Report
            </h3>
            @if($submission)
            <span class="text-xs text-[#94A3B8]">
                Last updated: {{ \Carbon\Carbon::parse($submission->updated_at)->format('M d, Y') }}
            </span>
            @endif
        </div>

        @if($submission)
        <div class="bg-[#60A5FA]/10 border border-[#60A5FA]/30 rounded-xl p-4 mb-6 text-[#60A5FA] text-xs flex items-start gap-2">
            <span class="material-icons text-sm">info</span>
            <span>You already submitted this task. You can update your report below.</span>
        </div>
        @endif

        <form method="POST" action="/tasks/{{ $task->id }}/submit" class="space-y-6">
            @csrf

            <!-- Step by Step -->
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold flex items-center gap-2">
                    <span class="material-icons text-sm text-[#4ADE80]">looks_one</span>
                    What did you do step by step? *
                </label>
                <textarea name="step_by_step" rows="5" required
                    placeholder="Describe your approach step by step. What commands did you run? What tools did you use? What was your workflow?"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-[#F1F5F9] focus:border-[#4ADE80]/50 focus:outline-none text-sm">{{ old('step_by_step', $submission->step_by_step ?? '') }}</textarea>
            </div>

            <!-- Observations -->
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold flex items-center gap-2">
                    <span class="material-icons text-sm text-[#4ADE80]">looks_two</span>
                    What did you notice during the task? *
                </label>
                <textarea name="observations" rows="5" required
                    placeholder="What anomalies, challenges, or interesting findings did you encounter? Any unexpected behavior? Any vulnerabilities discovered?"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-[#F1F5F9] focus:border-[#4ADE80]/50 focus:outline-none text-sm">{{ old('observations', $submission->observations ?? '') }}</textarea>
            </div>

            <!-- Lessons Learned -->
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold flex items-center gap-2">
                    <span class="material-icons text-sm text-[#4ADE80]">looks_3</span>
                    What did you learn? *
                </label>
                <textarea name="lessons_learned" rows="5" required
                    placeholder="What tools, techniques, or concepts did you master? What would you do differently next time?"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-[#F1F5F9] focus:border-[#4ADE80]/50 focus:outline-none text-sm">{{ old('lessons_learned', $submission->lessons_learned ?? '') }}</textarea>
            </div>

            <!-- Difficulty Rating -->
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-3 block font-bold flex items-center gap-2">
                    <span class="material-icons text-sm text-[#4ADE80]">star</span>
                    How difficult was this task? *
                </label>
                <div class="flex gap-3 flex-wrap">
                    @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="difficulty_rating" value="{{ $i }}"
                            {{ old('difficulty_rating', $submission->difficulty_rating ?? 3) == $i ? 'checked' : '' }}
                            class="peer hidden" required>
                        <div class="w-12 h-12 rounded-full glass flex items-center justify-center font-bold text-[#94A3B8] peer-checked:bg-gradient-to-r peer-checked:from-[#4ADE80] peer-checked:to-[#60A5FA] peer-checked:text-[#0A0A0F] peer-checked:scale-110 hover:border-[#4ADE80]/50 transition-all cursor-pointer">
                            {{ $i }}
                        </div>
                    </label>
                    @endfor
                </div>
                <div class="flex justify-between text-[10px] text-[#94A3B8] mt-2 max-w-[300px]">
                    <span>Easy</span>
                    <span>Very Hard</span>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-3 block font-bold flex items-center gap-2">
                    <span class="material-icons text-sm text-[#4ADE80]">flag</span>
                    Task Status *
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="completed"
                            {{ old('status', $submission->status ?? 'completed') == 'completed' ? 'checked' : '' }}
                            class="peer hidden" required>
                        <div class="p-4 rounded-xl glass text-center font-bold text-sm peer-checked:bg-[#4ADE80]/20 peer-checked:border-[#4ADE80] peer-checked:text-[#4ADE80] transition-all">
                            <span class="material-icons text-base align-middle mr-1">check_circle</span>
                            Completed
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="in_progress"
                            {{ old('status', $submission->status ?? '') == 'in_progress' ? 'checked' : '' }}
                            class="peer hidden" required>
                        <div class="p-4 rounded-xl glass text-center font-bold text-sm peer-checked:bg-orange-500/20 peer-checked:border-orange-400 peer-checked:text-orange-400 transition-all">
                            <span class="material-icons text-base align-middle mr-1">pending</span>
                            In Progress
                        </div>
                    </label>
                </div>
            </div>

            <!-- XP Notice -->
            @if(!$submission || !$submission->xp_awarded)
            <div class="bg-gradient-to-r from-[#4ADE80]/10 to-[#60A5FA]/10 border border-[#4ADE80]/20 rounded-xl p-4 text-center">
                <p class="text-xs text-[#4ADE80] font-bold flex items-center justify-center gap-2">
                    <span class="material-icons text-sm">info</span>
                    Submit as "Completed" — Admin will review and award <strong>+{{ $task->points }} XP</strong>
                </p>
            </div>
            @endif

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-lg glow-green hover:scale-105 transition-all flex items-center justify-center gap-2">
                <span class="material-icons">send</span>
                {{ $submission ? 'Update Report' : 'Submit Report' }}
            </button>
        </form>
    </div>

</div>
@endsection