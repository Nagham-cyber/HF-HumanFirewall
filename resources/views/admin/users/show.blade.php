@extends('admin.layouts.app')

@section('title', $user->name . ' - User Details')

@section('content')

<!-- Back -->
<a href="{{ route('admin.users.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Users
</a>

<!-- User Header -->
<div class="card p-8 mb-6">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-3xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-black">{{ $user->name }}</h1>
                <p class="text-sm text-[#94A3B8]">{{ $user->email }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-[#4ADE80]/10 text-[#4ADE80]' : 'bg-red-500/10 text-red-400' }}">
                        {{ $user->is_active ? 'ACTIVE' : 'BANNED' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-[#60A5FA]/10 text-[#60A5FA]">
                        {{ $stats['rank'] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.users.ban', $user->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-full font-bold text-xs {{ $user->is_active ? 'bg-red-500/20 text-red-400' : 'bg-[#4ADE80]/20 text-[#4ADE80]' }}">
                    <span class="material-icons text-sm align-middle">{{ $user->is_active ? 'block' : 'check_circle' }}</span>
                    {{ $user->is_active ? 'Ban User' : 'Unban User' }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-3xl text-[#4ADE80] mb-2">stars</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['xp'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total XP</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-3xl text-orange-400 mb-2">local_fire_department</span>
        <p class="text-3xl font-black text-orange-400">{{ $stats['streak'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Day Streak</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-3xl text-[#60A5FA] mb-2">menu_book</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['completed_scenarios'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Scenarios</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-3xl text-purple-400 mb-2">assignment_turned_in</span>
        <p class="text-3xl font-black text-purple-400">{{ $stats['completed_tasks'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Completed Tasks</p>
    </div>
</div>

<!-- Second Row Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-orange-400">{{ $stats['in_progress_tasks'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">In Progress</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-red-400">{{ $stats['total_mistakes'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Mistakes</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-black text-[#4ADE80]">{{ $stats['reviewed_mistakes'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Reviewed</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-sm font-black text-[#94A3B8]">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Joined</p>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Submissions -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">assignment</span>
            Recent Task Submissions
        </h3>
        <div class="space-y-2">
            @forelse($recentSubmissions as $sub)
            <a href="{{ route('admin.tasks.submission.show', $sub->id) }}" class="flex items-center justify-between p-3 bg-[#0A0A0F]/50 rounded-xl hover:bg-[#4ADE80]/5 transition-all">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold truncate">{{ $sub->task_title }}</p>
                    <p class="text-[10px] text-[#94A3B8]">{{ $sub->reference_code }} • {{ \Carbon\Carbon::parse($sub->created_at)->diffForHumans() }}</p>
                </div>
                <span class="px-2 py-1 rounded-full text-[10px] font-bold shrink-0
                    {{ $sub->status == 'completed' ? 'bg-[#4ADE80]/10 text-[#4ADE80]' : 'bg-orange-500/10 text-orange-400' }}">
                    {{ ucfirst($sub->status) }}
                </span>
            </a>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No submissions yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Mistakes -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-red-400">bug_report</span>
            Recent Mistakes
        </h3>
        <div class="space-y-2">
            @forelse($recentMistakes as $mistake)
            <div class="p-3 bg-[#0A0A0F]/50 rounded-xl">
                <p class="text-xs font-bold truncate">{{ $mistake->question ?? 'Unknown' }}</p>
                <p class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($mistake->created_at)->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No mistakes. Great job!</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Admin Actions -->
<div class="card p-6 mt-6">
    <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
        <span class="material-icons text-[#60A5FA]">settings</span>
        Admin Actions
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Update User Info -->
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-3">
            @csrf
            <h4 class="text-xs uppercase text-[#94A3B8] font-bold">Update Info</h4>
            <input type="text" name="name" value="{{ $user->name }}" required placeholder="Name"
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            <input type="email" name="email" value="{{ $user->email }}" required placeholder="Email"
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            <button type="submit" class="w-full py-2 bg-[#4ADE80] text-[#0A0A0F] rounded-full font-bold text-xs">Save Changes</button>
        </form>

        <!-- Reset Password -->
        <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" class="space-y-3">
            @csrf
            <h4 class="text-xs uppercase text-[#94A3B8] font-bold">Reset Password</h4>
            <input type="password" name="password" required placeholder="New Password"
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            <input type="password" name="password_confirmation" required placeholder="Confirm Password"
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            <button type="submit" class="w-full py-2 bg-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs">Reset Password</button>
        </form>

    </div>
</div>

@endsection