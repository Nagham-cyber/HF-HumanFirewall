@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    
    <div class="text-center mb-12">
        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-4xl font-black text-[#0A0A0F] glow-green mb-4">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <h1 class="text-3xl font-black">{{ $user->name }}</h1>
        <p class="text-[#4ADE80] text-sm">{{ $stats['security_level'] }}</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        <div class="card p-5 text-center"><span class="material-icons text-2xl text-[#4ADE80] mb-2">stars</span><p class="text-2xl font-black text-[#4ADE80]">{{ $stats['total_points'] }}</p><p class="text-[10px] text-[#94A3B8]">XP</p></div>
        <div class="card p-5 text-center"><span class="material-icons text-2xl text-[#60A5FA] mb-2">task_alt</span><p class="text-2xl font-black text-[#60A5FA]">{{ $stats['completed_scenarios'] }}</p><p class="text-[10px] text-[#94A3B8]">Scenarios</p></div>
        <div class="card p-5 text-center"><span class="material-icons text-2xl text-[#4ADE80] mb-2">military_tech</span><p class="text-2xl font-black text-[#4ADE80]">{{ $stats['badges_earned'] }}</p><p class="text-[10px] text-[#94A3B8]">Badges</p></div>
        <div class="card p-5 text-center"><span class="material-icons text-2xl text-orange-400 mb-2">local_fire_department</span><p class="text-2xl font-black text-orange-400">{{ $stats['streak_days'] }}</p><p class="text-[10px] text-[#94A3B8]">Streak</p></div>
    </div>

    @if(count($earnedBadges) > 0)
    <div class="card p-8 mb-8">
        <h3 class="font-bold text-sm text-[#94A3B8] uppercase mb-6">My Badges</h3>
        <div class="flex flex-wrap gap-4">
            @foreach($earnedBadges as $badge)
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br {{ $badge->color }} flex items-center justify-center">
                    <span class="material-icons text-3xl text-[#0A0A0F]">{{ $badge->icon }}</span>
                </div>
                <p class="text-[10px] text-[#94A3B8] mt-2">{{ $badge->name }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="card p-8 mb-8">
        <h3 class="font-bold text-sm text-[#94A3B8] uppercase mb-6">Edit Profile</h3>
        <form method="POST" action="/profile/update" class="space-y-4">
            @csrf
            <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
            <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green">Save</button>
        </form>
    </div>

    <div class="card p-8 border-red-500/20">
        <h3 class="font-bold text-sm text-red-400 uppercase mb-4">Danger Zone</h3>
        <form method="POST" action="/profile/delete" onsubmit="return confirm('Delete permanently?')">
            @csrf
            <input type="password" name="password" placeholder="Password" required class="w-full bg-[#0A0A0F] border border-red-500/20 rounded-xl px-4 py-3 mb-4">
            <button type="submit" class="px-6 py-3 bg-red-600 rounded-full font-bold">Delete Account</button>
        </form>
    </div>
</div>
@endsection