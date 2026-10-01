@extends('admin.layouts.app')

@section('title', 'Backups')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">backup</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Backups</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">sd_storage</span>
        <p class="text-2xl font-black text-[#60A5FA]">{{ $stats['total_size'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Size</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-purple-400 mb-2">schedule</span>
        <p class="text-xs font-black text-purple-400">{{ $stats['last_backup'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Last Backup</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-orange-400 mb-2">storage</span>
        <p class="text-2xl font-black text-orange-400">{{ $stats['db_size'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Database Size</p>
    </div>
</div>

<!-- Create Backup -->
<div class="card p-6 mb-6">
    <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
        <span class="material-icons text-base">add_circle</span> Create New Backup
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Database Only -->
        <form method="POST" action="{{ route('admin.backups.create') }}">
            @csrf
            <input type="hidden" name="type" value="db">
            <div class="p-4 bg-[#0A0A0F]/50 rounded-xl border border-[#4ADE80]/20">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-2xl text-[#4ADE80]">storage</span>
                    <div>
                        <p class="font-bold text-sm">Database Only</p>
                        <p class="text-[10px] text-[#94A3B8]">Fast • SQLite file</p>
                    </div>
                </div>
                <p class="text-xs text-[#94A3B8] mb-3">Backup only the database file (.sqlite). Perfect for quick recovery.</p>
                <button type="submit" class="w-full py-2 bg-[#4ADE80] text-[#0A0A0F] rounded-full font-bold text-xs">
                    Create DB Backup
                </button>
            </div>
        </form>

        <!-- Full Backup -->
        <form method="POST" action="{{ route('admin.backups.create') }}">
            @csrf
            <input type="hidden" name="type" value="full">
            <div class="p-4 bg-[#0A0A0F]/50 rounded-xl border border-[#60A5FA]/20">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-2xl text-[#60A5FA]">folder_zip</span>
                    <div>
                        <p class="font-bold text-sm">Full Backup</p>
                        <p class="text-[10px] text-[#94A3B8]">Slow • ZIP file</p>
                    </div>
                </div>
                <p class="text-xs text-[#94A3B8] mb-3">Backup database + app files + views + routes + config (without vendor).</p>
                <button type="submit" class="w-full py-2 bg-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs">
                    Create Full Backup
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Backups List -->
<div class="card p-6">
    <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
        <span class="material-icons text-[#4ADE80]">history</span>
        All Backups ({{ count($backups) }})
    </h3>

    <div class="space-y-2">
        @forelse($backups as $backup)
        <div class="flex items-center justify-between p-4 bg-[#0A0A0F]/50 rounded-xl">
            <div class="flex items-center gap-3 flex-1 min-w-0">

                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ str_contains($backup['name'], 'full') ? 'bg-[#60A5FA]/10' : 'bg-[#4ADE80]/10' }}">
                    <span class="material-icons text-lg {{ str_contains($backup['name'], 'full') ? 'text-[#60A5FA]' : 'text-[#4ADE80]' }}">
                        {{ str_ends_with($backup['name'], '.zip') ? 'folder_zip' : 'storage' }}
                    </span>
                </div>

                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold font-mono truncate">{{ $backup['name'] }}</p>
                    <p class="text-[10px] text-[#94A3B8]">
                        {{ $backup['size'] }} • {{ $backup['created_at'] }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 ml-4 shrink-0">
                <!-- Download -->
                <a href="{{ route('admin.backups.download', $backup['name']) }}" class="p-2 rounded-lg hover:bg-[#4ADE80]/10 text-[#4ADE80]" title="Download">
                    <span class="material-icons text-lg">download</span>
                </a>

                <!-- Restore -->
                <form method="POST" action="{{ route('admin.backups.restore', $backup['name']) }}" onsubmit="return confirm('Restore this backup? Current data will be replaced. A safety backup will be created automatically.')">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg hover:bg-orange-500/10 text-orange-400" title="Restore">
                        <span class="material-icons text-lg">restore</span>
                    </button>
                </form>

                <!-- Delete -->
                <form method="POST" action="{{ route('admin.backups.delete', $backup['name']) }}" onsubmit="return confirm('Delete this backup permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400" title="Delete">
                        <span class="material-icons text-lg">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <span class="material-icons text-6xl text-[#94A3B8] mb-4">backup</span>
            <h3 class="text-xl font-black mb-2">No Backups Yet</h3>
            <p class="text-[#94A3B8] text-sm">Create your first backup using the buttons above.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Info -->
<div class="card p-4 mt-6 border-l-4 border-yellow-500">
    <p class="text-xs text-[#94A3B8] flex items-start gap-2">
        <span class="material-icons text-yellow-400 text-sm mt-0.5">info</span>
        <span>
            <strong class="text-yellow-400">Backup Location:</strong> <code class="text-[#4ADE80]">storage/app/backups/</code><br>
            <strong class="text-yellow-400">Recommended:</strong> Download backups regularly to external storage (Google Drive, Dropbox, etc.).<br>
            <strong class="text-yellow-400">Auto Safety Backup:</strong> Before each restore, a safety backup is created automatically.
        </span>
    </p>
</div>

@endsection