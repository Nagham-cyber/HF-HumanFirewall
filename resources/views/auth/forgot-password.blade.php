<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - HF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { background: #0A0A0F; font-family: 'Space Grotesk', sans-serif; color: #F1F5F9; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(74,222,128,0.1); }
        .glow-green { box-shadow: 0 0 40px rgba(74,222,128,0.15); }
        .material-icons { font-family: 'Material Icons'; font-weight: normal; font-style: normal; display: inline-block; line-height: 1; text-transform: none; letter-spacing: normal; white-space: nowrap; direction: ltr; -webkit-font-smoothing: antialiased; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="glass rounded-3xl p-10 max-w-md w-full glow-green">
        <div class="text-center mb-8">
            <img src="{{ asset('pic/logo1.png') }}" alt="HF" class="w-16 h-16 object-contain mx-auto mb-4">
            <h1 class="text-2xl font-black">Forgot Password</h1>
        </div>
        
        <div id="step1">
            <div class="relative mb-4">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]">mail</span>
                <input type="email" id="emailInput" placeholder="Email" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl pl-10 pr-4 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <button onclick="sendCode()" class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green">Send Code</button>
        </div>
        
        <div id="step2" class="hidden">
            <div class="relative mb-4">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]">pin</span>
                <input type="text" id="codeInput" placeholder="Code (6 digits)" maxlength="6" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl pl-10 pr-4 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <div class="relative mb-4">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]">lock</span>
                <input type="password" id="newPassword" placeholder="New Password" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl pl-10 pr-4 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <button onclick="resetPassword()" class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green">Reset Password</button>
        </div>
        
        <p class="text-center mt-4"><a href="/login" class="text-[#4ADE80] text-sm">Back to Login</a></p>
    </div>

<script>
let userEmail = '';
function sendCode() {
    const email = document.getElementById('emailInput').value;
    if (!email) { alert('Enter email!'); return; }
    fetch('/forgot-password/send-code', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify({email: email})
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message);
        if (d.success) { userEmail = email; document.getElementById('step1').classList.add('hidden'); document.getElementById('step2').classList.remove('hidden'); }
    });
}
function resetPassword() {
    const code = document.getElementById('codeInput').value;
    const password = document.getElementById('newPassword').value;
    fetch('/forgot-password/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify({email: userEmail, code: code, password: password, password_confirmation: password})
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message);
        if (d.success) window.location.href = '/login';
    });
}
</script>
</body>
</html>