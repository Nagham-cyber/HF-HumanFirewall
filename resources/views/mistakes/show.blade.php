@extends('layouts.app')

@section('title', 'Mistake Review')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    
    <div class="text-center mb-10">
        <h1 class="text-3xl font-black">Mistake Review</h1>
        <p class="text-[#94A3B8] text-sm mt-2">Review your mistake and verify your understanding</p>
    </div>

    <!-- Scenario -->
    <div class="card p-8 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">visibility</span> Scenario
        </h3>
        <p class="leading-relaxed">{{ $scenarioText }}</p>
    </div>

    <!-- Question + Answers -->
    <div class="card p-8 mb-6">
        <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">help_outline</span> Question
        </h3>
        <p class="text-lg font-bold mb-6">{{ $question }}</p>
        
        <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-4">
            <p class="text-xs text-red-400 mb-2 flex items-center gap-2">
                <span class="material-icons text-sm">cancel</span> Your Answer
            </p>
            <p class="text-sm">{{ $mistake->user_answer }}</p>
        </div>
        <div class="bg-[#4ADE80]/10 border border-[#4ADE80]/20 rounded-xl p-4 mb-6">
            <p class="text-xs text-[#4ADE80] mb-2 flex items-center gap-2">
                <span class="material-icons text-sm">check_circle</span> Correct Answer
            </p>
            <p class="text-sm">{{ $mistake->correct_answer }}</p>
        </div>
        <div class="bg-[#0A0A0F]/50 rounded-xl p-6">
            <p class="text-xs text-[#4ADE80] uppercase mb-3 flex items-center gap-2">
                <span class="material-icons text-sm">lightbulb</span> Explanation
            </p>
            <p class="text-sm leading-relaxed">{{ $explanation }}</p>
        </div>
    </div>

    <!-- Retry Question -->
    <div class="card p-8 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">psychology</span> Verify Your Understanding
        </h3>
        <p class="text-lg font-bold mb-6">{{ $retryQuestion['question'] }}</p>
        
        <div class="space-y-3" id="retryOptions">
            @foreach($retryQuestion['options'] as $index => $option)
            <button 
                onclick="checkRetry(this, {{ $option['correct'] ? 'true' : 'false' }})" 
                class="w-full p-4 bg-[#0A0A0F]/50 border-2 border-[#4ADE80]/10 rounded-xl text-left hover:border-[#4ADE80]/40 transition-all retry-btn">
                <span class="material-icons text-base align-middle mr-2 text-[#4ADE80]">radio_button_unchecked</span>{{ $option['text'] }}
            </button>
            @endforeach
        </div>
        <div id="retryResult" class="hidden mt-4"></div>
    </div>

    <!-- Back Button -->
    <div class="text-center">
        <a href="{{ route('mistakes.index') }}" class="inline-flex items-center gap-2 px-6 py-3 glass rounded-full font-bold text-sm text-[#94A3B8] hover:text-[#4ADE80] transition-all">
            <span class="material-icons">arrow_back</span> Back to Vault
        </a>
    </div>
</div>

<script>
let reviewedSent = false;

function checkRetry(btn, correct) {
    document.querySelectorAll('.retry-btn').forEach(b => { 
        b.disabled = true; 
        b.classList.add('opacity-50'); 
    });
    
    const r = document.getElementById('retryResult');
    r.classList.remove('hidden');
    
    if (correct) {
        btn.classList.add('border-[#4ADE80]', 'bg-[#4ADE80]/10');
        btn.classList.remove('opacity-50', 'border-[#4ADE80]/10');
        
        r.innerHTML = `
            <div class="bg-[#4ADE80]/10 border border-[#4ADE80]/30 rounded-xl p-6 text-center">
                <span class="material-icons text-4xl text-[#4ADE80] mb-2">verified</span>
                <h3 class="font-bold text-lg text-[#4ADE80] mb-2">Excellent!</h3>
                <p class="text-sm text-[#94A3B8] mb-4">You understand this concept now!</p>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <a href="{{ route('mistakes.index') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm">
                        <span class="material-icons text-sm">arrow_back</span> Back to Vault
                    </a>
                </div>
            </div>
        `;
        
        // إرسال طلب لتحديث حالة المراجعة
        if (!reviewedSent) {
            reviewedSent = true;
            markAsReviewed();
        }
    } else {
        btn.classList.add('border-red-400', 'bg-red-500/10');
        btn.classList.remove('opacity-50', 'border-[#4ADE80]/10');
        
        r.innerHTML = `
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-6 text-center">
                <span class="material-icons text-4xl text-red-400 mb-2">refresh</span>
                <h3 class="font-bold text-lg text-red-400 mb-2">Not Quite</h3>
                <p class="text-sm text-[#94A3B8] mb-4">Remember: always choose the safest option!</p>
                <button onclick="resetRetry()" class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm">
                    <span class="material-icons text-sm">refresh</span> Try Again
                </button>
            </div>
        `;
    }
}

function resetRetry() {
    document.querySelectorAll('.retry-btn').forEach(b => { 
        b.disabled = false; 
        b.classList.remove('opacity-50', 'border-red-400', 'bg-red-500/10');
        b.classList.add('border-[#4ADE80]/10');
    });
    document.getElementById('retryResult').classList.add('hidden');
    document.getElementById('retryResult').innerHTML = '';
}

function markAsReviewed() {
    fetch('/mistakes/{{ $mistake->id }}/review', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Review status updated:', data);
    })
    .catch(error => {
        console.error('Error marking as reviewed:', error);
    });
}
</script>
@endsection