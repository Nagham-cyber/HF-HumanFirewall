@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">mail</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total_messages'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Messages</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-orange-400 mb-2">mark_email_unread</span>
        <p class="text-3xl font-black text-orange-400">{{ $stats['unread'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Unread</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-purple-400 mb-2">campaign</span>
        <p class="text-3xl font-black text-purple-400">{{ $stats['announcements'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Announcements</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">forum</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['active_conversations'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Conversations</p>
    </div>
</div>

<!-- Encryption Notice -->
<div class="card p-4 mb-6 border-l-4 border-[#4ADE80]">
    <p class="text-xs text-[#94A3B8] flex items-center gap-2">
        <span class="material-icons text-[#4ADE80]">lock</span>
        <strong class="text-[#4ADE80]">End-to-End Encrypted:</strong>
        All messages are encrypted with AES-256-GCM. Even if the database is compromised, messages cannot be read without the app key.
    </p>
</div>

<!-- Tabs -->
<div class="flex gap-3 mb-6 flex-wrap">
    <button onclick="switchTab('direct')" id="tab-direct" class="px-5 py-2 rounded-full font-bold text-sm bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F]">
        <span class="material-icons text-sm align-middle mr-1">chat</span> Direct Messages
    </button>
    <button onclick="switchTab('announcement')" id="tab-announcement" class="px-5 py-2 rounded-full font-bold text-sm glass text-[#94A3B8]">
        <span class="material-icons text-sm align-middle mr-1">campaign</span> Announcements
    </button>
    <button onclick="switchTab('new')" id="tab-new" class="px-5 py-2 rounded-full font-bold text-sm glass text-[#94A3B8]">
        <span class="material-icons text-sm align-middle mr-1">add</span> New Message
    </button>
</div>

<!-- Tab: Direct Messages -->
<div id="content-direct">
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">chat</span>
            Active Conversations ({{ $usersWithMessages->count() }})
        </h3>

        <div class="space-y-2">
            @forelse($usersWithMessages as $u)
            <div class="flex items-center gap-3 p-4 bg-[#0A0A0F]/50 rounded-xl hover:bg-[#4ADE80]/5 transition-all">
                <a href="{{ route('admin.messages.conversation', $u->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-black">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm">{{ $u->name }}</p>
                        <p class="text-xs text-[#94A3B8] truncate">{{ $u->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-[#4ADE80]">{{ $u->message_count }} msg</p>
                        <p class="text-[10px] text-[#94A3B8]">{{ \Carbon\Carbon::parse($u->last_message)->diffForHumans() }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.messages.conversation', $u->id) }}" class="p-2 rounded-lg hover:bg-[#4ADE80]/10 text-[#4ADE80]" title="Open Chat">
                    <span class="material-icons text-lg">arrow_forward</span>
                </a>
                <form method="POST" action="{{ route('admin.messages.conversation.delete', $u->id) }}" onsubmit="return confirm('Delete conversation with {{ $u->name }}? This cannot be undone.')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400" title="Delete Conversation">
                        <span class="material-icons text-lg">delete</span>
                    </button>
                </form>
            </div>
            @empty
            <div class="text-center py-12">
                <span class="material-icons text-5xl text-[#94A3B8] mb-3">inbox</span>
                <p class="text-[#94A3B8] text-sm">No conversations yet</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Tab: Announcements -->
<div id="content-announcement" class="hidden">
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-purple-400">campaign</span>
            Send New Announcement
        </h3>

        <form method="POST" action="{{ route('admin.messages.announcement') }}" class="space-y-4">
            @csrf
            <input type="text" name="title" required placeholder="Announcement Title"
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">

            <textarea name="body" rows="4" required placeholder="Write your announcement..."
                class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none"></textarea>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-500 to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-sm hover:scale-105 transition-all flex items-center justify-center gap-2">
                <span class="material-icons">send</span>
                Send to All Users
            </button>
        </form>
    </div>

    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#60A5FA]">history</span>
            Recent Announcements
        </h3>

        <div class="space-y-2">
            @forelse($announcements as $ann)
            <div class="p-4 bg-[#0A0A0F]/50 rounded-xl">
                <p class="font-bold text-sm">{{ $ann->decrypted_subject }}</p>
                <p class="text-xs text-[#94A3B8] mt-1">{{ Str::limit($ann->decrypted_body, 150) }}</p>
                <p class="text-[10px] text-[#94A3B8] mt-2">{{ \Carbon\Carbon::parse($ann->created_at)->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-xs text-[#94A3B8] text-center py-4">No announcements yet.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Tab: New Message -->
<div id="content-new" class="hidden">
    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">add_comment</span>
            Send Direct Message
        </h3>

        <form method="POST" action="{{ route('admin.messages.send') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Recipient *</label>
                <select name="receiver_id" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                    <option value="">-- Select User --</option>
                    @foreach($allUsers as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Subject *</label>
                <input type="text" name="subject" required placeholder="Message subject"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Message *</label>
                <textarea name="body" rows="6" required placeholder="Write your message..."
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none"></textarea>
            </div>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-sm hover:scale-105 transition-all flex items-center justify-center gap-2">
                <span class="material-icons">send</span>
                Send Message (Encrypted)
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function switchTab(tab) {
        document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));

        document.querySelectorAll('[id^="tab-"]').forEach(el => {
            el.classList.remove('bg-gradient-to-r', 'from-[#4ADE80]', 'to-[#60A5FA]', 'text-[#0A0A0F]');
            el.classList.add('glass', 'text-[#94A3B8]');
        });

        document.getElementById('content-' + tab).classList.remove('hidden');

        const btn = document.getElementById('tab-' + tab);
        btn.classList.remove('glass', 'text-[#94A3B8]');
        btn.classList.add('bg-gradient-to-r', 'from-[#4ADE80]', 'to-[#60A5FA]', 'text-[#0A0A0F]');
    }
</script>
@endsection