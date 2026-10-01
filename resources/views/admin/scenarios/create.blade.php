@extends('admin.layouts.app')

@section('title', 'Create Scenario')

@section('content')

<a href="{{ route('admin.scenarios.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Scenarios
</a>

<form method="POST" action="{{ route('admin.scenarios.store') }}">
    @csrf

    <!-- Basic Info -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">info</span> Basic Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="md:col-span-2">
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g., The CEO Email"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Description *</label>
                <textarea name="description" rows="2" required placeholder="Brief one-line description..."
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('description') }}</textarea>
            </div>

                        <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Category *</label>
                <input type="text" name="category" value="{{ old('category') }}" required
                    placeholder="e.g., phishing, network, cloud..."
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                <p class="text-[10px] text-[#94A3B8] mt-1">Write any category — will be created automatically.</p>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">User Type *</label>
                <select name="user_type" required class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">
                    <option value="general" {{ old('user_type') == 'general' ? 'selected' : '' }}>General User</option>
                    <option value="it_professional" {{ old('user_type') == 'it_professional' ? 'selected' : '' }}>IT Professional</option>
                    <option value="security_expert" {{ old('user_type') == 'security_expert' ? 'selected' : '' }}>Security Expert</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Level *</label>
                <select name="level" required class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">
                    <option value="easy" {{ old('level') == 'easy' ? 'selected' : '' }}>Easy (+10 XP)</option>
                    <option value="medium" {{ old('level') == 'medium' ? 'selected' : '' }}>Medium (+20 XP)</option>
                    <option value="hard" {{ old('level') == 'hard' ? 'selected' : '' }}>Hard (+30 XP)</option>
                    <option value="expert" {{ old('level') == 'expert' ? 'selected' : '' }}>Expert (+50 XP)</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Estimated Minutes *</label>
                <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', 5) }}" min="1" max="60" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">XP Points *</label>
                <input type="number" name="points" value="{{ old('points', 10) }}" min="5" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">
            </div>

        </div>
    </div>

    <!-- Scenario Content -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">article</span> Scenario Content
        </h3>

        <div class="space-y-4">
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Scenario Story *</label>
                <textarea name="scenario_text" rows="5" required placeholder="Tell the full story..."
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('scenario_text') }}</textarea>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Question *</label>
                <textarea name="question" rows="2" required placeholder="What is the question?"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('question') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Options -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">checklist</span> Answer Options
        </h3>

        <p class="text-xs text-[#94A3B8] mb-4">Select the correct answer using the radio button</p>

        <div class="space-y-3">
            @for($i = 0; $i < 4; $i++)
            <div class="flex items-center gap-3 p-3 bg-[#0A0A0F]/50 rounded-xl">
                <input type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} required class="accent-[#4ADE80]">
                <input type="text" name="options[]" placeholder="Option {{ $i + 1 }}" required
                    class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            </div>
            @endfor
        </div>
    </div>

    <!-- Explanation -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-purple-400 uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">lightbulb</span> Explanation
        </h3>
        <textarea name="explanation" rows="4" required placeholder="Explain why the correct answer is right..."
            class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('explanation') }}</textarea>
    </div>

    <!-- Submit -->
    <div class="card p-6 flex items-center justify-between">
        <p class="text-xs text-[#94A3B8]">The scenario will be immediately visible to users.</p>
        <div class="flex gap-3">
            <a href="{{ route('admin.scenarios.index') }}" class="px-6 py-3 rounded-full bg-[#0A0A0F] border border-[#4ADE80]/20 text-[#94A3B8] font-bold text-sm">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] font-black text-sm glow-green hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-icons">save</span> Create Scenario
            </button>
        </div>
    </div>

</form>

@endsection