@extends('admin.layouts.app')

@section('title', 'Chat with ' . $user->name)

@section('content')

<!-- Back -->
<a href="{{ route('admin.messages.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Messages
</a>

<!-- User Header -->
<div class="card p-4 mb-6 flex items-center gap-4 flex-wrap">
    <div class="w-14 h-14 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-xl">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div class="flex-1 min-w-0">
        <h2 class="font-black text-lg">{{ $user->name }}</h2>
        <p class="text-xs text-[#94A3B8]">{{ $user->email }}</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 text-[10px] text-[#4ADE80]">
            <span class="material-icons text-sm">lock</span>
            End-to-End Encrypted
        </div>
        <form method="POST" action="{{ route('admin.messages.conversation.delete', $user->id) }}" onsubmit="return confirm('Delete this entire conversation? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded-full bg-red-500/20 text-red-400 text-xs font-bold hover:bg-red-500/30 transition-all flex items-center gap-1">
                <span class="material-icons text-sm">delete</span> Delete Chat
            </button>
        </form>
    </div>
</div>

<!-- Messages -->
<div class="card p-6 mb-6" style="max-height: 500px; overflow-y: auto;">
    @forelse($messages as $msg)
    <div class="{{ $msg->sender_type == 'admin' ? 'text-right' : 'text-left' }} mb-4">
        <div class="inline-block max-w-[75%] rounded-2xl px-4 py-3
            {{ $msg->sender_type == 'admin' ? 'bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F]' : 'bg-[#0A0A0F]/70 text-[#F1F5F9] border border-[#4ADE80]/20' }}">

            <p class="text-xs font-bold mb-1 {{ $msg->sender_type == 'admin' ? 'text-[#0A0A0F]/80' : 'text-[#4ADE80]' }}">
                {{ $msg->decrypted_subject }}
            </p>

            <p class="text-sm whitespace-pre-wrap">{{ $msg->decrypted_body }}</p>

            <p class="text-[10px] mt-2 {{ $msg->sender_type == 'admin' ? 'text-[#0A0A0F]/60' : 'text-[#94A3B8]' }}">
                {{ \Carbon\Carbon::parse($msg->created_at)->format('M d, H:i') }}
            </p>
        </div>
    </div>
    @empty
    <div class="text-center py-16">
        <span class="material-icons text-5xl text-[#94A3B8] mb-3">forum</span>
        <p class="text-[#94A3B8] text-sm">No messages yet. Start the conversation!</p>
    </div>
    @endforelse
</div>

<!-- Reply Form -->
<div class="card p-6">
    <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
        <span class="material-icons text-base">reply</span> Send Reply
    </h3>

    <form method="POST" action="{{ route('admin.messages.send') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $user->id }}">

        <input type="text" name="subject" required placeholder="Subject"
            class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">

        <textarea name="body" rows="4" required placeholder="Type your reply..."
            class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none"></textarea>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black text-sm hover:scale-105 transition-all flex items-center justify-center gap-2">
            <span class="material-icons">send</span>
            Send Encrypted Reply
        </button>
    </form>
</div>

@endsection