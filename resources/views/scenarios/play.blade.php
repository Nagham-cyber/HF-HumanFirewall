@extends('layouts.app')

@section('title', 'Scenario')

@section('content')
@php
    $content = json_decode($scenario->content ?? '{}', true);
    $scenarioText = $content['scenario'] ?? $scenario->description ?? '';
    $question = $content['question'] ?? '';
    $options = $content['options'] ?? [];
    $explanation = $content['explanation'] ?? '';
@endphp

<div class="max-w-3xl mx-auto py-8 px-4">

    <!-- Header -->
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold
                @if($scenario->level == 'easy') bg-[#4ADE80]/10 text-[#4ADE80]
                @elseif($scenario->level == 'medium') bg-yellow-500/10 text-yellow-400
                @elseif($scenario->level == 'hard') bg-orange-500/10 text-orange-400
                @else bg-red-500/10 text-red-400 @endif">
                {{ ucfirst($scenario->level) }}
            </span>
            <a href="{{ route('scenarios.index') }}" class="material-icons text-[#94A3B8] hover:text-[#4ADE80]">close</a>
        </div>
        <h1 class="text-xl font-black">{{ $scenario->title }}</h1>
    </div>

    <!-- Scenario Text -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-3 flex items-center gap-2">
            <span class="material-icons text-base">visibility</span> Scenario
        </h3>
        <p class="text-sm leading-relaxed">{{ $scenarioText }}</p>
    </div>

    <!-- Question -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-3 flex items-center gap-2">
            <span class="material-icons text-base">help_outline</span> Question
        </h3>
        <p class="text-lg font-bold mb-6">{{ $question }}</p>

        <!-- Options -->
        <div class="space-y-3" id="optionsContainer">
            @foreach($options as $index => $option)
            <button 
                type="button"
                data-index="{{ $index }}"
                data-correct="{{ !empty($option['correct']) ? 'true' : 'false' }}"
                class="option-btn w-full p-4 bg-[#0A0A0F]/50 border-2 border-[#4ADE80]/10 rounded-xl text-left hover:border-[#4ADE80]/40 transition-all flex items-center gap-3">
                <span class="material-icons text-[#94A3B8] option-icon">radio_button_unchecked</span>
                <span class="text-sm">{{ $option['text'] }}</span>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Submit Button -->
    <button 
        type="button"
        id="submitBtn"
        onclick="submitAnswer()"
        disabled
        class="w-full px-8 py-4 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-lg transition-all opacity-50 cursor-not-allowed">
        <span class="material-icons text-base align-middle mr-2">send</span>
        Select an answer first
    </button>

    <!-- Result Box -->
    <div id="resultBox" class="hidden mt-6"></div>
</div>

<script>
let selectedIndex = null;
let selectedCorrect = false;
let isSubmitting = false;

// إضافة event listener لكل خيار
document.querySelectorAll('.option-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (isSubmitting) return;
        
        // إزالة التحديد من جميع الخيارات
        document.querySelectorAll('.option-btn').forEach(b => {
            b.classList.remove('border-[#4ADE80]', 'bg-[#4ADE80]/10');
            b.classList.add('border-[#4ADE80]/10');
            b.querySelector('.option-icon').textContent = 'radio_button_unchecked';
            b.querySelector('.option-icon').classList.remove('text-[#4ADE80]');
            b.querySelector('.option-icon').classList.add('text-[#94A3B8]');
        });

        // تحديد الخيار المختار
        this.classList.remove('border-[#4ADE80]/10');
        this.classList.add('border-[#4ADE80]', 'bg-[#4ADE80]/10');
        this.querySelector('.option-icon').textContent = 'radio_button_checked';
        this.querySelector('.option-icon').classList.add('text-[#4ADE80]');
        this.querySelector('.option-icon').classList.remove('text-[#94A3B8]');

        selectedIndex = parseInt(this.dataset.index);
        selectedCorrect = this.dataset.correct === 'true';

        // تفعيل زر الإرسال
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        submitBtn.innerHTML = '<span class="material-icons text-base align-middle mr-2">send</span> Submit Answer';
    });
});

function submitAnswer() {
    if (selectedIndex === null || isSubmitting) return;

    isSubmitting = true;
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-icons animate-spin align-middle mr-2">refresh</span> Submitting...';

    // تعطيل الخيارات
    document.querySelectorAll('.option-btn').forEach(b => {
        b.disabled = true;
        b.classList.add('cursor-not-allowed');
    });

    fetch('/scenarios/{{ $scenario->id }}/complete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            is_correct: selectedCorrect,
            selected_option: selectedIndex
        })
    })
    .then(response => response.json())
    .then(data => {
        showResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        isSubmitting = false;
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="material-icons text-base align-middle mr-2">send</span> Submit Answer';
        alert('An error occurred. Please try again.');
    });
}

function showResult(data) {
    // تلوين الخيارات
    document.querySelectorAll('.option-btn').forEach(btn => {
        const isCorrect = btn.dataset.correct === 'true';
        const isSelected = parseInt(btn.dataset.index) === selectedIndex;

        if (isCorrect) {
            btn.classList.add('border-[#4ADE80]', 'bg-[#4ADE80]/10');
            btn.querySelector('.option-icon').textContent = 'check_circle';
            btn.querySelector('.option-icon').classList.add('text-[#4ADE80]');
            btn.querySelector('.option-icon').classList.remove('text-[#94A3B8]');
        } else if (isSelected) {
            btn.classList.add('border-red-500', 'bg-red-500/10');
            btn.querySelector('.option-icon').textContent = 'cancel';
            btn.querySelector('.option-icon').classList.add('text-red-400');
            btn.querySelector('.option-icon').classList.remove('text-[#94A3B8]');
        }
    });

    // إخفاء زر الإرسال
    document.getElementById('submitBtn').classList.add('hidden');

    // عرض النتيجة
    const resultBox = document.getElementById('resultBox');
    resultBox.classList.remove('hidden');

    const isCorrect = data.success === true;
    const xpEarned = data.xp_earned || 0;

    if (isCorrect) {
        resultBox.innerHTML = `
            <div class="card p-8 text-center">
                <span class="material-icons text-6xl text-[#4ADE80] mb-4">verified</span>
                <h2 class="text-2xl font-black text-[#4ADE80] mb-2">Excellent!</h2>
                <p class="text-sm text-[#94A3B8] mb-4">${data.message || 'Correct answer!'}</p>
                ${xpEarned > 0 ? `<p class="text-lg font-bold text-[#4ADE80] mb-6">+${xpEarned} XP</p>` : ''}
                <div class="flex flex-wrap justify-center gap-3 mt-4">
                    <a href="{{ route('scenarios.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold">
                        <span class="material-icons text-sm">arrow_back</span> All Scenarios
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 glass rounded-full font-bold text-[#4ADE80]">
                        <span class="material-icons text-sm">dashboard</span> Dashboard
                    </a>
                </div>
            </div>
        `;
    } else {
        resultBox.innerHTML = `
            <div class="card p-8 text-center">
                <span class="material-icons text-6xl text-red-400 mb-4">cancel</span>
                <h2 class="text-2xl font-black text-red-400 mb-2">Wrong Answer</h2>
                <p class="text-sm text-[#94A3B8] mb-4">The correct answer has been highlighted above. Review it in your Mistake Vault.</p>
                <div class="bg-[#0A0A0F]/50 rounded-xl p-4 mb-6 text-left">
                    <p class="text-xs text-[#4ADE80] uppercase mb-2">Explanation</p>
                    <p class="text-sm">{{ $explanation }}</p>
                </div>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('mistakes.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-red-500/20 text-red-400 rounded-full font-bold border border-red-500/30">
                        <span class="material-icons text-sm">bug_report</span> View Mistakes
                    </a>
                    <a href="{{ route('scenarios.index') }}" class="inline-flex items-center gap-2 px-6 py-3 glass rounded-full font-bold text-[#4ADE80]">
                        <span class="material-icons text-sm">arrow_back</span> All Scenarios
                    </a>
                </div>
            </div>
        `;
    }
}
</script>
@endsection