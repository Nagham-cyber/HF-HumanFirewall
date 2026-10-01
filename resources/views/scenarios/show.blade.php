@extends('layouts.app')

@section('title', $scenario->title ?? 'Scenario')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    @if($scenario)
    <div class="card p-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-icons text-3xl text-[#4ADE80]">@if($scenario->category == 'phishing') phishing @elseif($scenario->category == 'password') key @elseif($scenario->category == 'malware') bug_report @else shield @endif</span>
            <h1 class="text-3xl font-black">{{ $scenario->title }}</h1>
        </div>
        <p class="text-[#94A3B8] mb-6">{{ $scenario->description }}</p>
        <div class="flex gap-4 mb-6">
            <span class="px-3 py-1 bg-[#4ADE80]/10 text-[#4ADE80] text-xs font-bold rounded-full">{{ ucfirst($scenario->level) }}</span>
            <span class="text-xs text-[#94A3B8] flex items-center gap-1"><span class="material-icons text-sm">timer</span> {{ $scenario->estimated_minutes }} min</span>
        </div>
        <a href="/scenarios/{{ $scenario->id }}/play" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green">
            <span class="material-icons">play_arrow</span> Start
        </a>
    </div>
    @endif
</div>
@endsection