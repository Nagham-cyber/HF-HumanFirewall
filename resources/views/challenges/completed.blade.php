@extends('layouts.app')

@section('title', 'Challenge Completed')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl {{ $correct ? 'text-[#4ADE80]' : 'text-red-400' }} mb-4">{{ $correct ? 'check_circle' : 'cancel' }}</span>
        <h1 class="text-3xl font-black mb-2">{{ $correct ? 'Challenge Completed!' : 'Challenge Attempted' }}</h1>
        <p class="text-[#94A3B8] mb-6">Come back in 24 hours for new challenges!</p>
        <div class="flex items-center justify-center gap-2 mb-6">
            <span class="material-icons text-orange-400">local_fire_department</span>
            <span class="font-bold text-[#4ADE80]">{{ Auth::user()->streak_days }} day streak</span>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold">Dashboard</a>
    </div>
</div>
@endsection