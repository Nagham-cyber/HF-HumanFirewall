@extends('admin.layouts.app')

@section('title', 'Create Daily Challenges')

@section('content')

<a href="{{ route('admin.challenges.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Challenges
</a>

<form method="POST" action="{{ route('admin.challenges.storeThree') }}">
    @csrf

    <!-- Date -->
    <div class="card p-6 mb-6 text-center">
        <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Challenge Date</label>
        <input type="date" name="challenge_date" value="{{ old('challenge_date', now()->format('Y-m-d')) }}" required
            class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-6 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
        <p class="text-[10px] text-[#94A3B8] mt-2">All 3 challenges will be for this date</p>
    </div>

    <!-- Easy Challenge -->
    <div class="card p-6 mb-6 border-l-4 border-[#4ADE80]">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-[#4ADE80] uppercase flex items-center gap-2">
                <span class="material-icons text-base">leaf</span> Easy Challenge
            </h3>
            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">+5 XP</span>
        </div>

        <div class="space-y-4">
            <textarea name="easy_scenario" rows="3" required placeholder="Scenario story..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('easy_scenario') }}</textarea>

            <textarea name="easy_question" rows="2" required placeholder="Question..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('easy_question') }}</textarea>

            <div class="space-y-2">
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3 p-2 bg-[#0A0A0F]/50 rounded-xl">
                    <input type="radio" name="easy_correct" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} required class="accent-[#4ADE80]">
                    <input type="text" name="easy_options[]" placeholder="Option {{ $i + 1 }}" required
                        class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
                </div>
                @endfor
            </div>

            <textarea name="easy_explanation" rows="2" required placeholder="Explanation..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('easy_explanation') }}</textarea>
        </div>
    </div>

    <!-- Medium Challenge -->
    <div class="card p-6 mb-6 border-l-4 border-yellow-400">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-yellow-400 uppercase flex items-center gap-2">
                <span class="material-icons text-base">shield</span> Medium Challenge
            </h3>
            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-500/10 text-yellow-400">+10 XP</span>
        </div>

        <div class="space-y-4">
            <textarea name="medium_scenario" rows="3" required placeholder="Scenario story..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('medium_scenario') }}</textarea>

            <textarea name="medium_question" rows="2" required placeholder="Question..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('medium_question') }}</textarea>

            <div class="space-y-2">
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3 p-2 bg-[#0A0A0F]/50 rounded-xl">
                    <input type="radio" name="medium_correct" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} required class="accent-yellow-400">
                    <input type="text" name="medium_options[]" placeholder="Option {{ $i + 1 }}" required
                        class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
                </div>
                @endfor
            </div>

            <textarea name="medium_explanation" rows="2" required placeholder="Explanation..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('medium_explanation') }}</textarea>
        </div>
    </div>

    <!-- Hard Challenge -->
    <div class="card p-6 mb-6 border-l-4 border-red-400">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-red-400 uppercase flex items-center gap-2">
                <span class="material-icons text-base">security</span> Hard Challenge
            </h3>
            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400">+15 XP</span>
        </div>

        <div class="space-y-4">
            <textarea name="hard_scenario" rows="3" required placeholder="Scenario story..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('hard_scenario') }}</textarea>

            <textarea name="hard_question" rows="2" required placeholder="Question..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('hard_question') }}</textarea>

            <div class="space-y-2">
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3 p-2 bg-[#0A0A0F]/50 rounded-xl">
                    <input type="radio" name="hard_correct" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} required class="accent-red-400">
                    <input type="text" name="hard_options[]" placeholder="Option {{ $i + 1 }}" required
                        class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
                </div>
                @endfor
            </div>

            <textarea name="hard_explanation" rows="2" required placeholder="Explanation..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">{{ old('hard_explanation') }}</textarea>
        </div>
    </div>

    <!-- Submit -->
    <div class="card p-6 flex items-center justify-between">
        <p class="text-xs text-[#94A3B8]">
            <span class="material-icons text-sm align-middle">info</span>
            All 3 challenges will be saved at once.
        </p>
        <div class="flex gap-3">
            <a href="{{ route('admin.challenges.index') }}" class="px-6 py-3 rounded-full bg-[#0A0A0F] border border-[#4ADE80]/20 text-[#94A3B8] font-bold text-sm">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] font-black text-sm glow-green hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-icons">save</span> Save All 3 Challenges
            </button>
        </div>
    </div>

</form>

@endsection