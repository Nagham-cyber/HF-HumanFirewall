@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')

<!-- Admin Info Card -->
<div class="card p-6 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-3xl">
                {{ strtoupper(substr($admin->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-black">{{ $admin->name }}</h1>
                <p class="text-sm text-[#94A3B8]">{{ $admin->email }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">
                        {{ strtoupper($admin->role) }}
                    </span>
                    @if($admin->two_factor_enabled)
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-[#60A5FA]/10 text-[#60A5FA]">2FA ON</span>
                    @else
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400">2FA OFF</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-right text-xs text-[#94A3B8]">
            <p><strong class="text-[#F1F5F9]">Last Login:</strong> {{ $admin->last_login_at ? \Carbon\Carbon::parse($admin->last_login_at)->diffForHumans() : 'Never' }}</p>
            <p class="mt-1"><strong class="text-[#F1F5F9]">Last IP:</strong> {{ $admin->last_login_ip ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Update Profile -->
    <div class="card p-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">person</span> Profile Information
        </h3>

        <form method="POST" action="{{ route('admin.settings.profile') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <button type="submit" class="w-full py-3 bg-[#4ADE80] text-[#0A0A0F] rounded-full font-bold text-sm hover:scale-105 transition-all">
                Save Profile
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="card p-6">
        <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">lock</span> Change Password
        </h3>

        <form method="POST" action="{{ route('admin.settings.password') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Current Password</label>
                <input type="password" name="current_password" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">New Password</label>
                <input type="password" name="new_password" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                <p class="text-[10px] text-[#94A3B8] mt-1">Min 10 chars, uppercase, lowercase, number, special char</p>
            </div>
            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <button type="submit" class="w-full py-3 bg-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm hover:scale-105 transition-all">
                Change Password
            </button>
        </form>
    </div>

</div>

<!-- Grid 2 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- 2FA -->
    <div class="card p-6">
        <h3 class="font-bold text-sm text-purple-400 uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">verified_user</span> Two-Factor Authentication
        </h3>

        <p class="text-xs text-[#94A3B8] mb-4 leading-relaxed">
            When enabled, you'll receive a 6-digit verification code via email each time you log in.
            This adds an extra layer of security to your account.
        </p>

        <div class="flex items-center justify-between p-4 bg-[#0A0A0F]/50 rounded-xl mb-4">
            <div>
                <p class="text-sm font-bold">Status</p>
                <p class="text-xs {{ $admin->two_factor_enabled ? 'text-[#4ADE80]' : 'text-red-400' }}">
                    {{ $admin->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                </p>
            </div>
            <form method="POST" action="{{ route('admin.settings.2fa') }}">
                @csrf
                <button type="submit" class="px-6 py-2 rounded-full font-bold text-sm transition-all {{ $admin->two_factor_enabled ? 'bg-red-500/20 text-red-400 hover:bg-red-500/30' : 'bg-[#4ADE80]/20 text-[#4ADE80] hover:bg-[#4ADE80]/30' }}">
                    {{ $admin->two_factor_enabled ? 'Disable' : 'Enable' }} 2FA
                </button>
            </form>
        </div>
    </div>

    <!-- Allowed IPs -->
    <div class="card p-6">
        <h3 class="font-bold text-sm text-yellow-400 uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">security</span> IP Whitelisting
        </h3>

        <p class="text-xs text-[#94A3B8] mb-4 leading-relaxed">
            Restrict admin access to specific IP addresses. Leave empty to allow all IPs.
            One IP per line.
        </p>

        <form method="POST" action="{{ route('admin.settings.allowed-ips') }}" class="space-y-3">
            @csrf
            @php
                $currentIPs = $admin->allowed_ips ? implode("\n", json_decode($admin->allowed_ips, true) ?? []) : '';
            @endphp
            <textarea name="allowed_ips" rows="4" placeholder="192.168.1.100&#10;10.0.0.5&#10;203.0.113.42"
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm font-mono focus:border-[#4ADE80]/50 focus:outline-none">{{ old('allowed_ips', $currentIPs) }}</textarea>
            <button type="submit" class="w-full py-3 bg-yellow-500/20 text-yellow-400 rounded-full font-bold text-sm hover:bg-yellow-500/30 transition-all">
                Update Allowed IPs
            </button>
        </form>
    </div>

</div>

<!-- Recent Activity -->
<div class="card p-6">
    <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
        <span class="material-icons text-[#4ADE80]">history</span>
        Recent Activity (Last 10 Actions)
    </h3>

    <div class="space-y-2">
        @forelse($recentLogs as $log)
        <div class="flex items-center justify-between p-3 bg-[#0A0A0F]/50 rounded-xl">
            <div class="flex items-center gap-3">
                <span class="material-icons text-sm
                    @if(str_contains($log->action, 'login')) text-[#4ADE80]
                    @elseif(str_contains($log->action, 'password')) text-red-400
                    @elseif(str_contains($log->action, '2fa')) text-purple-400
                    @else text-[#60A5FA] @endif">
                    @if(str_contains($log->action, 'login')) login
                    @elseif(str_contains($log->action, 'password')) lock
                    @elseif(str_contains($log->action, '2fa')) verified_user
                    @else activity @endif
                </span>
                <div>
                    <p class="text-xs font-bold">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>
                    <p class="text-[10px] text-[#94A3B8]">{{ $log->description }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</p>
                <p class="text-[10px] text-[#94A3B8] font-mono">{{ $log->ip_address }}</p>
            </div>
        </div>
        @empty
        <p class="text-xs text-[#94A3B8] text-center py-4">No activity yet.</p>
        @endforelse
    </div>
</div>

@endsection