@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="material-icons text-2xl text-[#4ADE80]">people</span>
            <span class="text-[10px] text-[#94A3B8] uppercase font-bold">Total</span>
        </div>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] mt-1">+{{ $stats['new_this_week'] }} this week</p>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="material-icons text-2xl text-[#60A5FA]">check_circle</span>
            <span class="text-[10px] text-[#94A3B8] uppercase font-bold">Active</span>
        </div>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['active'] }}</p>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="material-icons text-2xl text-red-400">block</span>
            <span class="text-[10px] text-[#94A3B8] uppercase font-bold">Banned</span>
        </div>
        <p class="text-3xl font-black text-red-400">{{ $stats['banned'] }}</p>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="material-icons text-2xl text-yellow-400">person_add</span>
            <span class="text-[10px] text-[#94A3B8] uppercase font-bold">New Today</span>
        </div>
        <p class="text-3xl font-black text-yellow-400">{{ $stats['new_today'] }}</p>
    </div>
</div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..."
            class="flex-1 min-w-[200px] bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
        
        <select name="status" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="banned" {{ $status == 'banned' ? 'selected' : '' }}>Banned</option>
        </select>

        <select name="sort" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="xp_points" {{ $sort == 'xp_points' ? 'selected' : '' }}>Sort: XP</option>
            <option value="created_at" {{ $sort == 'created_at' ? 'selected' : '' }}>Sort: Newest</option>
            <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Sort: Name</option>
            <option value="streak_days" {{ $sort == 'streak_days' ? 'selected' : '' }}>Sort: Streak</option>
        </select>

        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm">
            <span class="material-icons text-sm align-middle">search</span>
        </button>
    </form>
</div>

<!-- Users List -->
<div class="space-y-2">
    @forelse($users as $user)
    <div class="card p-4 flex items-center justify-between">
        <a href="{{ route('admin.users.show', $user->id) }}" class="flex items-center gap-4 flex-1 min-w-0">
            <div class="w-12 h-12 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-lg shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-sm {{ !$user->is_active ? 'text-red-400 line-through' : '' }}">{{ $user->name }}</p>
                    @if(!$user->is_active)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400">BANNED</span>
                    @endif
                </div>
                <p class="text-xs text-[#94A3B8] truncate">{{ $user->email }}</p>
            </div>

            <div class="flex items-center gap-4 text-xs shrink-0">
                <div class="text-center">
                    <p class="font-black text-[#4ADE80]">{{ $user->xp_points ?? 0 }}</p>
                    <p class="text-[9px] text-[#94A3B8] uppercase">XP</p>
                </div>
                <div class="text-center">
                    <p class="font-black text-orange-400">{{ $user->streak_days ?? 0 }}</p>
                    <p class="text-[9px] text-[#94A3B8] uppercase">Streak</p>
                </div>
                <div class="text-center">
                    <p class="font-black text-[#60A5FA]">{{ $user->security_rank ?? 'Novice' }}</p>
                    <p class="text-[9px] text-[#94A3B8] uppercase">Rank</p>
                </div>
            </div>
        </a>

        <div class="flex items-center gap-2 ml-4 shrink-0">
            <a href="{{ route('admin.users.show', $user->id) }}" class="p-2 rounded-lg hover:bg-[#4ADE80]/10 text-[#4ADE80]" title="View">
                <span class="material-icons text-lg">visibility</span>
            </a>

            <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 {{ $user->is_active ? 'text-red-400' : 'text-[#4ADE80]' }}" title="{{ $user->is_active ? 'Ban' : 'Unban' }}">
                    <span class="material-icons text-lg">{{ $user->is_active ? 'block' : 'check_circle' }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Delete this user permanently? This will remove all their data.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-600" title="Delete">
                    <span class="material-icons text-lg">delete</span>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#94A3B8] mb-4">people_outline</span>
        <p class="text-[#94A3B8]">No users found.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $users->appends(request()->query())->links() }}
</div>

@endsection