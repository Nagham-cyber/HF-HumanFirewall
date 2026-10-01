@extends('layouts.app')

@section('title', 'Cybersecurity News')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black mb-2">Cybersecurity <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">News</span></h1>
    </div>

    @if(count($news) > 0)
    <div class="space-y-4">
        @foreach($news as $item)
        <a href="{{ $item['link'] }}" target="_blank" class="card p-6 group hover:scale-105 block">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-[#4ADE80]/10 flex items-center justify-center shrink-0">
                    <span class="material-icons {{ $item['color'] }}">{{ $item['icon'] }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex gap-3 mb-2">
                        <span class="px-2 py-1 bg-[#4ADE80]/10 text-[#4ADE80] text-[10px] font-bold rounded-full">{{ $item['source'] }}</span>
                        <span class="text-xs text-[#94A3B8]">{{ $item['pubDate'] }}</span>
                    </div>
                    <h3 class="font-bold text-sm mb-2 group-hover:text-[#4ADE80]">{{ $item['title'] }}</h3>
                    <p class="text-xs text-[#94A3B8]">{{ $item['description'] }}</p>
                </div>
                <span class="material-icons text-[#94A3B8] group-hover:text-[#4ADE80]">open_in_new</span>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#4ADE80] mb-4">article</span>
        <h2 class="text-2xl font-black">No News Available</h2>
    </div>
    @endif
</div>
@endsection