@extends('layouts.app')

@section('title', 'Certificates')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">My <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Certificates</span></h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($certificates as $cert)
        <div class="card group p-8 text-center">
            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 bg-gradient-to-br {{ $cert['color'] }}">
                <span class="material-icons text-4xl text-[#0A0A0F]">workspace_premium</span>
            </div>
            <h3 class="text-lg font-bold mb-2">{{ $cert['name'] }}</h3>
            <p class="text-sm text-[#94A3B8] mb-4">{{ $cert['completed_scenarios'] }}/{{ $cert['total_scenarios'] }}</p>
            <div class="w-full h-1.5 bg-[#4ADE80]/10 rounded-full mb-4"><div class="h-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] rounded-full" style="width: {{ $cert['progress'] }}%"></div></div>
            <p class="font-bold {{ $cert['progress'] == 100 ? 'text-[#4ADE80]' : 'text-[#60A5FA]' }}">{{ $cert['progress'] }}%</p>
            
            @if($cert['is_earned'])
            <a href="/certificates/{{ $cert['type'] }}" class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green">
                <span class="material-icons text-sm">visibility</span> View
            </a>
            @else
            <a href="/scenarios?type={{ $cert['type'] }}" class="inline-flex items-center gap-2 mt-4 px-6 py-3 glass text-[#4ADE80] rounded-full font-bold">
                <span class="material-icons text-sm">lock</span> Complete
            </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection