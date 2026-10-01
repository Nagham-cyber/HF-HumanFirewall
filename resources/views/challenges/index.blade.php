@extends('layouts.app')

@section('title', 'Daily Challenge')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">

    <!-- Hero -->
    <div class="card p-10 mb-8 text-center glow-green">
        <div class="flex items-center justify-center gap-3 mb-3">
            <span class="material-icons text-orange-400 text-4xl">local_fire_department</span>
            <h1 class="text-3xl font-black">Daily Challenge</h1>
        </div>
        <p class="text-[#94A3B8] text-sm">Answer all 3 challenges to earn XP</p>
        
        <div class="flex items-center justify-center gap-3 mt-4">
            <div class="glass rounded-full px-4 py-2 flex items-center gap-2">
                <span class="material-icons text-orange-400 text-sm">local_fire_department</span>
                <span class="text-sm font-bold">{{ $streakDays ?? 0 }} day streak</span>
            </div>
            <div class="glass rounded-full px-4 py-2 flex items-center gap-2">
                <span class="material-icons text-[#4ADE80] text-sm">stars</span>
                <span class="text-sm font-bold">+30 XP possible</span>
            </div>
        </div>
    </div>

    @if($completedToday ?? false)
    <!-- Already Completed -->
    <div class="card p-10 text-center">
        <span class="material-icons text-6xl text-[#4ADE80] mb-4">check_circle</span>
        <h2 class="text-2xl font-black mb-2">Today's Challenge Completed!</h2>
        <p class="text-[#94A3B8] text-sm mb-6">You've already earned your XP for today. Come back tomorrow for new challenges!</p>
        
        <div class="flex items-center justify-center gap-4 mb-8">
            <div class="glass rounded-full px-6 py-3 flex items-center gap-2">
                <span class="material-icons text-orange-400">local_fire_department</span>
                <span class="text-lg font-bold">{{ $streakDays ?? 0 }} day streak</span>
            </div>
        </div>

        <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold hover:scale-105 transition-all">
            Back to Dashboard
        </a>
    </div>
    @else

    <!-- Challenges -->
    <div class="space-y-6 mb-8">
        @foreach($challenges as $challenge)
        <div class="card p-8" data-challenge-id="{{ $challenge->id }}">
            
            <div class="flex items-center justify-between mb-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if($challenge->difficulty == 'easy') bg-[#4ADE80]/10 text-[#4ADE80]
                    @elseif($challenge->difficulty == 'medium') bg-yellow-500/10 text-yellow-400
                    @else bg-red-500/10 text-red-400 @endif">
                    {{ ucfirst($challenge->difficulty) }} — +{{ $challenge->points }} XP
                </span>
                <span class="material-icons text-[#94A3B8]">
                    @if($challenge->difficulty == 'easy') leaf
                    @elseif($challenge->difficulty == 'medium') shield
                    @else security @endif
                </span>
            </div>

            <div class="bg-[#0A0A0F]/50 border border-[#4ADE80]/10 rounded-xl p-4 mb-4">
                <p class="text-xs uppercase tracking-widest text-[#4ADE80] mb-2">Scenario</p>
                <p class="text-[#F1F5F9] text-sm">{{ $challenge->scenario }}</p>
            </div>

            <div class="bg-[#0A0A0F]/50 border border-[#60A5FA]/10 rounded-xl p-4 mb-4">
                <p class="text-xs uppercase tracking-widest text-[#60A5FA] mb-2">Question</p>
                <p class="text-[#F1F5F9] font-bold text-sm">{{ $challenge->question }}</p>
            </div>

            @php 
                $options = is_string($challenge->options) ? json_decode($challenge->options, true) : $challenge->options;
            @endphp

            <div class="space-y-3">
                @foreach($options as $index => $option)
                <label class="flex items-center p-4 bg-[#0A0A0F]/50 border-2 border-[#4ADE80]/10 rounded-xl cursor-pointer hover:border-[#4ADE80]/40 transition-all option-label">
                   <input type="radio" 
    name="challenge_{{ $challenge->id }}" 
    value="{{ $index }}"
    class="mr-3 w-5 h-5 accent-[#4ADE80]">
                    <span class="text-[#F1F5F9] text-sm">{{ $option['text'] }}</span>
                </label>
                @endforeach
            </div>

            <div class="result-box hidden mt-4 p-4 rounded-xl border"></div>
        </div>
        @endforeach
    </div>

    <button onclick="submitAllChallenges()" 
        id="submitBtn"
        class="w-full px-8 py-4 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-lg glow-green hover:scale-105 transition-all flex items-center justify-center gap-3">
        <span class="material-icons">send</span>
        Submit All Answers
    </button>

    @endif
</div>

<script>
function submitAllChallenges() {
    const challengeCards = document.querySelectorAll('[data-challenge-id]');
    const answers = [];
    let allAnswered = true;

   challengeCards.forEach(card => {
    const challengeId = card.dataset.challengeId;
    const selected = card.querySelector('input[type="radio"]:checked');
    
    if (!selected) {
        allAnswered = false;
        return;
    }

    answers.push({
        challenge_id: parseInt(challengeId),
        selected_option: parseInt(selected.value)
    });
});

    if (!allAnswered) {
        alert('Please answer all questions first!');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-icons animate-spin">refresh</span> Submitting...';

    fetch('/daily-challenge/submit-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ answers: answers })
    })
    .then(response => response.json())
    .then(data => {
        if (data.already_completed) {
            alert(data.message);
            location.reload();
            return;
        }
        showResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="material-icons">send</span> Submit All Answers';
    });
}

function showResult(data) {
    const challengeCards = document.querySelectorAll('[data-challenge-id]');
    const results = data.results || [];

    challengeCards.forEach(card => {
        const challengeId = parseInt(card.dataset.challengeId);
        const result = results.find(r => r.challenge_id === challengeId);
        
        if (!result) return;

        const selectedInput = card.querySelector('input[type="radio"]:checked');
        const resultBox = card.querySelector('.result-box');

        card.querySelectorAll('input[type="radio"]').forEach(input => {
            const label = input.closest('.option-label');
            const inputValue = parseInt(input.value);

            if (inputValue === result.correct) {
                label.classList.add('border-[#4ADE80]', 'bg-[#4ADE80]/10');
                label.classList.remove('border-[#4ADE80]/10');
            } else if (selectedInput === input && !result.is_correct) {
                label.classList.add('border-red-500', 'bg-red-500/10');
                label.classList.remove('border-[#4ADE80]/10');
            }
            
            input.disabled = true;
        });

        resultBox.classList.remove('hidden');
        if (result.is_correct) {
            resultBox.className = 'result-box mt-4 p-4 rounded-xl border border-[#4ADE80]/30 bg-[#4ADE80]/10 text-[#4ADE80]';
            resultBox.innerHTML = '<div class="flex items-center gap-2"><span class="material-icons">check_circle</span><span class="font-bold">Correct!</span></div>';
        } else {
            resultBox.className = 'result-box mt-4 p-4 rounded-xl border border-red-500/30 bg-red-500/10 text-red-400';
            resultBox.innerHTML = '<div class="flex items-center gap-2"><span class="material-icons">cancel</span><span class="font-bold">Wrong answer</span></div>';
        }
    });

    const correctCount = data.correct_count ?? 0;
    const totalChallenges = data.total_challenges ?? 3;
    const totalPoints = data.total_points ?? 0;
    const streak = data.streak ?? 0;
    const isSuccess = data.success ?? false;

    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4';
    overlay.innerHTML = `
        <div class="glass rounded-3xl p-8 max-w-md w-full text-center glow-green">
            <span class="material-icons text-6xl ${isSuccess ? 'text-[#4ADE80]' : 'text-red-400'} mb-4">
                ${isSuccess ? 'emoji_events' : 'info'}
            </span>
            <h2 class="text-2xl font-black mb-2">
                ${isSuccess ? 'Perfect!' : 'Challenges Submitted'}
            </h2>
            <p class="text-[#94A3B8] mb-4">Correct: ${correctCount} / ${totalChallenges}</p>
            <p class="text-lg font-bold ${totalPoints > 0 ? 'text-[#4ADE80]' : 'text-red-400'} mb-2">
                ${totalPoints > 0 ? '+' + totalPoints + ' XP Earned!' : 'No XP earned'}
            </p>
            <p class="text-sm text-orange-400 mb-6">Streak: ${streak} days</p>
            <p class="text-xs text-[#94A3B8] mb-6">Come back tomorrow for new challenges!</p>
            <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold">
                Back to Dashboard
            </a>
        </div>
    `;
    document.body.appendChild(overlay);

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-icons">check</span> Submitted!';
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
}
</script>
@endsection