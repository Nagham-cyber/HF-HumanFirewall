@extends('admin.layouts.app')

@section('title', 'Audit Logs')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">list_alt</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Actions</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">today</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['today'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Today</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-orange-400 mb-2">date_range</span>
        <p class="text-3xl font-black text-orange-400">{{ $stats['this_week'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">This Week</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-purple-400 mb-2">category</span>
        <p class="text-3xl font-black text-purple-400">{{ $stats['unique_actions'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Action Types</p>
    </div>
</div>

<!-- Top Actions -->
<div class="card p-6 mb-6">
    <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
        <span class="material-icons text-[#4ADE80]">trending_up</span>
        Top 5 Actions
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
        @foreach($topActions as $top)
        <div class="p-3 bg-[#0A0A0F]/50 rounded-xl text-center">
            <p class="text-xs font-bold text-[#4ADE80] truncate" title="{{ $top->action }}">{{ str_replace('_', ' ', $top->action) }}</p>
            <p class="text-2xl font-black text-[#60A5FA] mt-1">{{ $top->count }}</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search..."
            class="md:col-span-2 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm focus:border-[#4ADE80]/50 focus:outline-none">

        <select name="action" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all">All Actions</option>
            @foreach($uniqueActions as $act)
            <option value="{{ $act }}" {{ $action == $act ? 'selected' : '' }}>{{ str_replace('_', ' ', $act) }}</option>
            @endforeach
        </select>

        <select name="admin_id" class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <option value="all">All Admins</option>
            @foreach($admins as $adm)
            <option value="{{ $adm->id }}" {{ $adminId == $adm->id ? 'selected' : '' }}>{{ $adm->name }}</option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ $dateFrom }}" placeholder="From"
            class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">

        <input type="date" name="date_to" value="{{ $dateTo }}" placeholder="To"
            class="bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">

        <div class="md:col-span-6 flex gap-3">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm flex items-center gap-1">
                <span class="material-icons text-sm">search</span> Filter
            </button>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-6 py-2 bg-[#0A0A0F] border border-[#4ADE80]/20 text-[#94A3B8] rounded-full font-bold text-sm">
                Clear
            </a>
            <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="px-6 py-2 bg-orange-500/20 text-orange-400 rounded-full font-bold text-sm flex items-center gap-1 ml-auto">
                <span class="material-icons text-sm">download</span> Export CSV
            </a>
        </div>
    </form>
</div>

<!-- Logs List -->
<div class="space-y-2">
    @forelse($logs as $log)
    <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="card p-4 flex items-center justify-between hover:scale-[1.01] transition-all">
        <div class="flex items-center gap-4 flex-1 min-w-0">

            <!-- Icon -->
            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                @if(str_contains($log->action, 'login')) bg-[#4ADE80]/10
                @elseif(str_contains($log->action, 'password')) bg-red-500/10
                @elseif(str_contains($log->action, '2fa')) bg-purple-500/10
                @elseif(str_contains($log->action, 'message')) bg-[#60A5FA]/10
                @elseif(str_contains($log->action, 'delete')) bg-red-500/10
                @else bg-[#4ADE80]/10 @endif">
                <span class="material-icons text-lg
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

            <!-- Info -->
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="font-bold text-sm">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>
                    <span class="text-[10px] text-[#94A3B8] font-mono">#{{ $log->id }}</span>
                </div>
                <p class="text-xs text-[#94A3B8] truncate">{{ $log->description ?? 'No description' }}</p>
                <div class="flex items-center gap-3 mt-1 text-[10px] text-[#94A3B8]">
                    <span class="flex items-center gap-1">
                        <span class="material-icons text-xs">person</span>
                        {{ $log->admin_name ?? 'System' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-icons text-xs">location_on</span>
                        {{ $log->ip_address }}
                    </span>
                </div>
            </div>

            <!-- Date -->
            <div class="text-right shrink-0">
                <p class="text-xs font-bold">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}</p>
                <p class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</p>
                <p class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</p>
            </div>
        </div>
    </a>
    @empty
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#94A3B8] mb-4">history</span>
        <h3 class="text-xl font-black mb-2">No Logs Found</h3>
        <p class="text-[#94A3B8] text-sm">Try adjusting your filters.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $logs->appends(request()->query())->links() }}
</div>

@endsection