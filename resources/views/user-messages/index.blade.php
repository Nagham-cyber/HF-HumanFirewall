@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">

    <!-- Hero -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-black mb-2">My <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Messages</span></h1>
        <p class="text-[#94A3B8] text-sm">Communicate securely with the security admin</p>
    </div>

    <!-- Encryption Notice -->
    <div class="card p-4 mb-6 border-l-4 border-[#4ADE80]">
        <p class="text-xs text-[#94A3B8] flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">lock</span>
            <strong class="text-[#4ADE80]">End-to-End Encrypted:</strong>
            All messages are encrypted with AES-256-GCM.
        </p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-3 mb-6 flex-wrap">
        <button onclick="switchTab('announcements')" id="tab-announcements" class="px-5 py-2 rounded-full font-bold text-sm bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F]">
            <span class="material-icons text-sm align-middle mr-1">campaign</span> Announcements
            @if($stats['unread_announcements'] > 0)
            <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white">{{ $stats['unread_announcements'] }}</span>
            @endif
        </button>
        <button onclick="switchTab('conversation')" id="tab-conversation" class="px-5 py-2 rounded-full font-bold text-sm glass text-[#94A3B8]">
            <span class="material-icons text-sm align-middle mr-1">chat</span> Chat with Admin
        </button>
        <button onclick="switchTab('new')" id="tab-new" class="px-5 py-2 rounded-full font-bold text-sm glass text-[#94A3B8]">
            <span class="material-icons text-sm align-middle mr-1">add</span> New Message
        </button>
    </div>

    <!-- Tab: Announcements -->
    <div id="content-announcements">
        @forelse($announcements as $ann)
        <div class="card p-5 mb-3 {{ !$ann->is_read ? 'border-l-4 border-[#4ADE80]' : '' }}">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ !$ann->is_read ? 'bg-[#4ADE80]/20' : 'bg-[#0A0A0F]/50' }}">
                    <span class="material-icons {{ !$ann->is_read ? 'text-[#4ADE80]' : 'text-[#94A3B8]' }}">campaign</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-bold text-sm">{{ $ann->decrypted_subject }}</p>
                        @if(!$ann->is_read)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#4ADE80]/20 text-[#4ADE80]">NEW</span>
                        @endif
                    </div>
                    <p class="text-sm text-[#94A3B8] mt-2 leading-relaxed whitespace-pre-wrap">{{ $ann->decrypted_body }}</p>
                    <p class="text-[10px] text-[#94A3B8] mt-3">{{ \Carbon\Carbon::parse($ann->created_at)->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="card p-16 text-center">
            <span class="material-icons text-6xl text-[#94A3B8] mb-4">campaign</span>
            <h3 class="text-xl font-black mb-2">No Announcements</h3>
            <p class="text-[#94A3B8] text-sm">No announcements from the admin yet.</p>
        </div>
        @endforelse
    </div>

    <!-- Tab: Conversation -->
    <div id="content-conversation" class="hidden">
        <div class="card p-6 mb-6" style="max-height: 500px; overflow-y: auto;">
            @forelse($directMessages as $msg)
            <div class="{{ $msg->sender_type == 'user' ? 'text-right' : 'text-left' }} mb-4">
                <div class="inline-block max-w-[75%] rounded-2xl px-4 py-3
                    {{ $msg->sender_type == 'user' ? 'bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F]' : 'bg-[#0A0A0F]/70 text-[#F1F5F9] border border-[#4ADE80]/20' }}">

                    <p class="text-xs font-bold mb-1 {{ $msg->sender_type == 'user' ? 'text-[#0A0A0F]/80' : 'text-[#4ADE80]' }}">
                        {{ $msg->sender_type == 'user' ? 'You' : 'Admin' }}: {{ $msg->decrypted_subject }}
                    </p>
                    <p class="text-sm whitespace-pre-wrap">{{ $msg->decrypted_body }}</p>
                    <p class="text-[10px] mt-2 {{ $msg->sender_type == 'user' ? 'text-[#0A0A0F]/60' : 'text-[#94A3B8]' }}">
                        {{ \Carbon\Carbon::parse($msg->created_at)->format('M d, H:i') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <span class="material-icons text-5xl text-[#94A3B8] mb-3">forum</span>
                <p class="text-[#94A3B8] text-sm">No conversation yet. Send your first message!</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Tab: New Message -->
    <div id="content-new" class="hidden">
        <div class="card p-6">
            <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
                <span class="material-icons text-base">send</span> Report Incident / Send Message
            </h3>

            <form method="POST" action="{{ route('messages.send') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Subject *</label>
                    <input type="text" name="subject" required placeholder="e.g., Suspicious activity detected"
                        class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                </div>
                <div>
                    <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Message *</label>
                    <textarea name="body" rows="6" required placeholder="Describe what happened..."
                        class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-sm hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <span class="material-icons">send</span>
                    Send to Admin (Encrypted)
                </button>
            </form>
        </div>
    </div>

</div>

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