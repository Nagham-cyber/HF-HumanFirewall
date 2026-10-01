@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    
    <div class="card overflow-hidden" style="height: calc(100vh - 10rem);">
        <div class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] p-4 flex items-center gap-3">
            <span class="material-icons text-[#0A0A0F]">smart_toy</span>
            <div>
                <h2 class="font-bold text-[#0A0A0F]">CyberGuard AI</h2>
                <p class="text-[#0A0A0F]/60 text-xs">OpenRouter</p>
            </div>
        </div>

        <div id="chatMessages" class="overflow-y-auto p-6 space-y-4" style="height: calc(100% - 8rem);">
            <div class="bg-[#0A0A0F]/50 rounded-2xl px-4 py-3 max-w-[80%] text-sm">Hello! Ask me about cybersecurity!</div>
        </div>

        <div class="border-t border-[#4ADE80]/10 p-4">
            <div class="flex gap-3">
                <input type="text" id="messageInput" placeholder="Type..."
                    class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-full px-5 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
                <button onclick="sendMessage()" class="px-6 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green">
                    <span class="material-icons">send</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const chatHistory = [];
function addMessage(role, content) {
    const div = document.createElement('div');
    div.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';
    div.innerHTML = role === 'user'
        ? `<div class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-2xl px-4 py-3 max-w-[80%] text-sm">${content}</div>`
        : `<div class="bg-[#0A0A0F]/50 rounded-2xl px-4 py-3 max-w-[80%] text-sm">${content}</div>`;
    document.getElementById('chatMessages').appendChild(div);
    div.scrollIntoView({behavior:'smooth'});
}
async function sendMessage() {
    const input = document.getElementById('messageInput');
    const msg = input.value.trim();
    if(!msg) return;
    addMessage('user', msg);
    input.value = '';
    const res = await fetch('/ai/chat', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body:JSON.stringify({message:msg,history:chatHistory})});
    const data = await res.json();
    addMessage('assistant', data.response || 'Error');
}
document.getElementById('messageInput').addEventListener('keypress', e => { if(e.key==='Enter') sendMessage(); });
</script>
@endsection