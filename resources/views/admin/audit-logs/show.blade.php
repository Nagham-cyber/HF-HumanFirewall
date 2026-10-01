@extends('admin.layouts.app')

@section('title', 'Log Details')

@section('content')

<!-- Back -->
<a href="{{ route('admin.audit-logs.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Audit Logs
</a>

<!-- Log Header -->
<div class="card p-8 mb-6">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full flex items-center justify-center
                @if(str_contains($log->action, 'login')) bg-[#4ADE80]/10
                @elseif(str_contains($log->action, 'password')) bg-red-500/10
                @elseif(str_contains($log->action, '2fa')) bg-purple-500/10
                @elseif(str_contains($log->action, 'message')) bg-[#60A5FA]/10
                @elseif(str_contains($log->action, 'delete')) bg-red-500/10
                @else bg-[#4ADE80]/10 @endif">
                <span class="material-icons text-3xl
                    @if(str_contains($log->action, 'login')) text-[#4ADE80]
                    @elseif(str_contains($log->action, 'password')) text-red-400
                    @elseif(str_contains($log->action, '2fa')) text-purple-400
                    @elseif(str_contains($log->action, 'message')) text-[#60A5FA]
                    @elseif(str_contains($log->action, 'delete')) text-red-400
                    @else text-[#4ADE80] @endif">
                    @if(str_contains($log->action, 'login')) login
                    @elseif(str_contains($log->action, 'password')) lock
                    @elseif(str_contains($log->action, '2fa')) verified_user
                    @elseif(str_contains($log->action, 'message')) mail
                    @elseif(str_contains($log->action, 'delete')) delete
                    @elseif(str_contains($log->action, 'create')) add_circle
                    @elseif(str_contains($log->action, 'update')) edit
                    @else activity @endif
                </span>
            </div>
            <div>
                <h1 class="text-2xl font-black">{{ str_replace('_', ' ', ucfirst($log->action)) }}</h1>
                <p class="text-sm text-[#94A3B8] mt-1">{{ $log->description ?? 'No description' }}</p>
                <p class="text-xs text-[#94A3B8] mt-2 font-mono">Log ID: #{{ $log->id }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Details Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

    <div class="card p-6">
        <h3 class="font-bold text-xs text-[#94A3B8] uppercase mb-3">Admin Information</h3>
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#94A3B8]">Name:</span>
                <span class="text-sm font-bold">{{ $log->admin_name ?? 'System' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#94A3B8]">Email:</span>
                <span class="text-xs">{{ $log->admin_email ?? 'N/A' }}</span>
            </div>
            @if($log->admin_role)
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#94A3B8]">Role:</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">{{ strtoupper($log->admin_role) }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-bold text-xs text-[#94A3B8] uppercase mb-3">Request Information</h3>
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#94A3B8]">IP Address:</span>
                <span class="text-xs font-mono font-bold">{{ $log->ip_address ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#94A3B8]">Date:</span>
                <span class="text-xs font-bold">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#94A3B8]">Ago:</span>
                <span class="text-xs">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
            </div>
        </div>
    </div>

</div>

<!-- User Agent -->
<div class="card p-6 mb-6">
    <h3 class="font-bold text-xs text-[#94A3B8] uppercase mb-3">User Agent</h3>
    <p class="text-xs text-[#94A3B8] font-mono break-all">{{ $log->user_agent ?? 'N/A' }}</p>
</div>

<!-- Metadata -->
@if($log->metadata)
<div class="card p-6">
    <h3 class="font-bold text-xs text-[#94A3B8] uppercase mb-3">Additional Data (JSON)</h3>
    <pre class="text-xs text-[#4ADE80] font-mono bg-[#0A0A0F] rounded-xl p-4 overflow-x-auto">{{ json_encode(json_decode($log->metadata), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
</div>
@endif

@endsection