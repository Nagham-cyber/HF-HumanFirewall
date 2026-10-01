@extends('admin.layouts.app')

@section('title', 'Security Dashboard')

@section('content')

<!-- Security Score -->
<div class="card p-8 mb-6 glow-green">
    <div class="flex items-center justify-between flex-wrap gap-6">
        <div class="flex items-center gap-4">
            <div class="w-24 h-24 rounded-full flex items-center justify-center
                @if($securityScore >= 80) bg-[#4ADE80]/20 border-4 border-[#4ADE80]
                @elseif($securityScore >= 60) bg-yellow-500/20 border-4 border-yellow-400
                @else bg-red-500/20 border-4 border-red-400 @endif">
                <span class="text-3xl font-black
                    @if($securityScore >= 80) text-[#4ADE80]
                    @elseif($securityScore >= 60) text-yellow-400
                    @else text-red-400 @endif">
                    {{ $securityScore }}%
                </span>
            </div>
            <div>
                <h1 class="text-2xl font-black">Security Score</h1>
                <p class="text-sm text-[#94A3B8] mt-1">
                    @if($securityScore >= 80) Excellent security posture
                    @elseif($securityScore >= 60) Good — some improvements needed
                    @else Needs urgent attention @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($securityChecks as $check => $passed)
            <div class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $passed ? 'bg-[#4ADE80]/10' : 'bg-red-500/10' }}">
                <span class="material-icons text-sm {{ $passed ? 'text-[#4ADE80]' : 'text-red-400' }}">
                    {{ $passed ? 'check_circle' : 'cancel' }}
                </span>
                <span class="text-[10px] font-bold">{{ str_replace('_', ' ', $check) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Login Attempts Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">login</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $loginAttempts['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Logins</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">today</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $loginAttempts['today'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Today</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-red-400 mb-2">error</span>
        <p class="text-3xl font-black text-red-400">{{ $loginAttempts['failed_today'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Failed Today</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-orange-400 mb-2">warning</span>
        <p class="text-3xl font-black text-orange-400">{{ $loginAttempts['failed_week'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Failed This Week</p>
    </div>
</div>

<!-- Suspicious IPs & Emails -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Suspicious IPs -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-red-400">warning</span>
            Suspicious IPs ({{ count($suspiciousIPs) }})
        </h3>
        <div class="space-y-2">
            @forelse($suspiciousIPs as $ip)
            <div class="flex items-center justify-between p-3 bg-red-500/5 rounded-xl border border-red-500/20">
                <span class="text-xs font-mono text-[#F1F5F9]">{{ $ip->ip_address }}</span>
                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-red-500/20 text-red-400">
                    {{ $ip->attempt_count }} attempts
                </span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No suspicious IPs detected.</p>
            @endforelse
        </div>
    </div>

    <!-- Suspicious Emails -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-orange-400">mail</span>
            Suspicious Emails ({{ count($suspiciousEmails) }})
        </h3>
        <div class="space-y-2">
            @forelse($suspiciousEmails as $email)
            <div class="flex items-center justify-between p-3 bg-orange-500/5 rounded-xl border border-orange-500/20">
                <span class="text-xs font-mono text-[#F1F5F9] truncate">{{ $email->email }}</span>
                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-orange-500/20 text-orange-400">
                    {{ $email->attempt_count }} attempts
                </span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No suspicious emails detected.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Admin Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">admin_panel_settings</span>
        <p class="text-2xl font-black text-[#4ADE80]">{{ $adminStats['total_admins'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Total Admins</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">check_circle</span>
        <p class="text-2xl font-black text-[#60A5FA]">{{ $adminStats['active_admins'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Active</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-red-400 mb-2">lock</span>
        <p class="text-2xl font-black text-red-400">{{ $adminStats['locked_admins'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">Locked</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-purple-400 mb-2">verified_user</span>
        <p class="text-2xl font-black text-purple-400">{{ $adminStats['2fa_enabled'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase">2FA Enabled</p>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Recent Logins -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">login</span>
            Recent Successful Logins
        </h3>
        <div class="space-y-2">
            @forelse($recentLogins as $login)
            <div class="flex items-center justify-between p-3 bg-[#0A0A0F]/50 rounded-xl">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold truncate">{{ $login->email ?? 'N/A' }}</p>
                    <p class="text-[10px] text-[#94A3B8] font-mono">{{ $login->ip_address }}</p>
                </div>
                <span class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($login->created_at)->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No recent logins.</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Failed Logins -->
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-red-400">error</span>
            Recent Failed Logins
        </h3>
        <div class="space-y-2">
            @forelse($recentFailedLogins as $fail)
            <div class="flex items-center justify-between p-3 bg-red-500/5 rounded-xl border border-red-500/20">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold truncate">{{ $fail->email ?? 'N/A' }}</p>
                    <p class="text-[10px] text-[#94A3B8] font-mono">{{ $fail->ip_address }}</p>
                </div>
                <span class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($fail->created_at)->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No recent failed logins.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Recent Audit Logs -->
<div class="card p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-sm flex items-center gap-2">
            <span class="material-icons text-[#60A5FA]">list_alt</span>
            Recent Admin Actions
        </h3>
        <a href="{{ route('admin.audit-logs.index') }}" class="text-xs text-[#4ADE80] hover:text-[#60A5FA]">View All →</a>
    </div>
    <div class="space-y-2">
        @forelse($recentAuditLogs as $log)
        <div class="flex items-center justify-between p-3 bg-[#0A0A0F]/50 rounded-xl">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <span class="material-icons text-sm
                    @if(str_contains($log->action, 'login')) text-[#4ADE80]
                    @elseif(str_contains($log->action, 'delete')) text-red-400
                    @else text-[#60A5FA] @endif">
                    @if(str_contains($log->action, 'login')) login
                    @elseif(str_contains($log->action, 'delete')) delete
                    @elseif(str_contains($log->action, 'create')) add_circle
                    @else edit @endif
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold truncate">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>
                    <p class="text-[10px] text-[#94A3B8] truncate">{{ $log->description }}</p>
                </div>
            </div>
            <span class="text-[10px] text-[#94A3B8] shrink-0">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
        </div>
        @empty
        <p class="text-xs text-[#94A3B8] text-center py-4">No recent actions.</p>
        @endforelse
    </div>
</div>

<!-- Clear Old Attempts -->
<div class="card p-6 mt-6">
    <h3 class="font-bold text-sm text-yellow-400 mb-4 flex items-center gap-2">
        <span class="material-icons">cleaning_services</span>
        Maintenance
    </h3>
    <form method="POST" action="{{ route('admin.security.clear-attempts') }}" class="flex gap-3 items-center">
        @csrf
        <label class="text-xs text-[#94A3B8]">Clear login attempts older than:</label>
        <select name="days" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="7">7 days</option>
            <option value="30" selected>30 days</option>
            <option value="90">90 days</option>
        </select>
        <button type="submit" class="px-6 py-2 bg-yellow-500/20 text-yellow-400 rounded-full font-bold text-sm hover:bg-yellow-500/30">
            Clear
        </button>
    </form>
</div>

@endsection