<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - HF</title>
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
            <span class="text-3xl font-black bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">HF</span>
        </div>
        @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6 text-red-400 text-sm">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/login" class="space-y-4">
            @csrf
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8] text-lg">mail</span>
                <input type="email" name="email" required placeholder="Email" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl pl-10 pr-4 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8] text-lg">lock</span>
                <input type="password" name="password" required placeholder="Password" class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl pl-10 pr-4 py-3 focus:border-[#4ADE80]/50 focus:outline-none">
            </div>
            <div class="text-right">
                <a href="/forgot-password" class="text-xs text-[#94A3B8] hover:text-[#4ADE80]">Forgot Password?</a>
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green flex items-center justify-center gap-2">
                <span class="material-icons text-lg">login</span> Sign In
            </button>
        </form>
        <p class="text-center text-[#94A3B8] text-sm mt-6">New here? <a href="/register" class="text-[#4ADE80]">Create Account</a></p>
    </div>
</body>
</html>