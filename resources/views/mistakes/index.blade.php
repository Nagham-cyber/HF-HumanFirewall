@extends('layouts.app')

@section('title', 'Mistake Vault')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">

    <!-- Hero -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">Mistake <span class="bg-gradient-to-r from-red-400 to-[#4ADE80] bg-clip-text text-transparent">Vault</span></h1>
        <p class="text-[#94A3B8] text-sm mt-2">Review your mistakes and learn from them</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-6 mb-12 max-w-2xl mx-auto">
        <div class="card p-6 text-center">
            <span class="material-icons text-2xl text-red-400 mb-2">bug_report</span>
            <p class="text-3xl font-black text-red-400">{{ $stats['total_mistakes'] }}</p>
            <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total</p>
        </div>
        <div class="card p-6 text-center">
            <span class="material-icons text-2xl text-[#4ADE80] mb-2">check_circle</span>
            <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['reviewed'] }}</p>
            <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Reviewed</p>
        </div>
        <div class="card p-6 text-center">
            <span class="material-icons text-2xl text-yellow-400 mb-2">pending</span>
            <p class="text-3xl font-black text-yellow-400">{{ $stats['unreviewed'] }}</p>
            <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Pending</p>
        </div>
    </div>

    <!-- Weak Areas -->
    @if(count($weakAreas) > 0)
    <div class="card p-8 mb-12 max-w-2xl mx-auto">
        <h3 class="font-bold text-sm text-[#94A3B8] uppercase tracking-widest mb-6">Weak Areas</h3>
        <div class="space-y-5">
            @foreach($weakAreas as $area)
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold flex items-center gap-2">
                        <span class="material-icons text-lg
                            @if($area['category'] == 'easy') text-[#4ADE80]
                            @elseif($area['category'] == 'medium') text-yellow-400
                            @elseif($area['category'] == 'hard') text-red-400
                            @else text-[#60A5FA] @endif">
                            @if($area['category'] == 'easy') leaf
                            @elseif($area['category'] == 'medium') shield
                            @elseif($area['category'] == 'hard') security
                            @else shield @endif
                        </span>
                        {{ ucfirst(str_replace('_', ' ', $area['category'])) }}
                    </span>
                    <span class="text-sm font-black text-[#4ADE80]">{{ $area['count'] }}</span>
                </div>
                <div class="w-full h-1.5 bg-[#4ADE80]/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-red-400 to-[#4ADE80] rounded-full"
                         style="width: {{ $area['percentage'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Mistakes List -->
    @if(count($mistakes) > 0)
    <div class="space-y-4 max-w-3xl mx-auto">
        @foreach($mistakes as $mistake)
        <a href="/mistakes/{{ $mistake->id }}" class="card p-6 group hover:scale-105 transition-all block">
            <div class="flex items-center justify-between mb-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if($mistake->is_reviewed) bg-[#4ADE80]/10 text-[#4ADE80]
                    @else bg-red-500/10 text-red-400 @endif">
                    @if($mistake->is_reviewed) Reviewed @else Needs Review @endif
                </span>
                <span class="material-icons text-[#94A3B8] group-hover:text-[#4ADE80] transition-colors">
                    @if($mistake->is_reviewed) check_circle @else arrow_forward @endif
                </span>
            </div>
            
            <h3 class="font-bold text-sm mb-2">{{ Str::limit($mistake->question ?? 'Scenario Question', 100) }}</h3>
            
            @if(isset($mistake->category))
            <span class="inline-block px-2 py-1 bg-[#60A5FA]/10 text-[#60A5FA] text-[10px] font-bold rounded-full mb-2">
                {{ ucfirst(str_replace('_', ' ', $mistake->category)) }}
            </span>
            @endif
            
            <p class="text-xs text-[#94A3B8]">{{ $mistake->created_at }}</p>
        </a>
        @endforeach
    </div>
    @else
    <div class="card p-16 text-center max-w-2xl mx-auto">
        <span class="material-icons text-6xl text-[#4ADE80] mb-4">verified</span>
        <h2 class="text-2xl font-black mb-2">No Mistakes Yet!</h2>
        <p class="text-[#94A3B8]">Keep up the excellent work!</p>
    </div>
    @endif
</div>
@endsection