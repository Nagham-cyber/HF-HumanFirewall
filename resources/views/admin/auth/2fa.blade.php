<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>2FA Verification - HF</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body { background: #0A0A0F; font-family: 'Inter', sans-serif; color: #F1F5F9; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(74,222,128,0.15); }
        .cyber-grid { background-image: linear-gradient(rgba(74,222,128,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(74,222,128,0.03) 1px, transparent 1px); background-size: 50px 50px; }
        .glow-green { box-shadow: 0 0 40px rgba(74,222,128,0.15); }
    </style>
</head>
<body class="cyber-grid min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="glass rounded-3xl p-10 text-center glow-green">
            <span class="material-icons text-5xl text-[#4ADE80] mb-4">verified_user</span>
            <h1 class="text-2xl font-black mb-2">Two-Factor Auth</h1>
            <p class="text-[#94A3B8] text-sm mb-6">Enter the 6-digit code sent to your email</p>

            @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 text-red-400 text-xs">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.2fa.verify') }}" class="space-y-4">
                @csrf
                <input type="text" name="code" maxlength="6" pattern="[0-9]{6}" required
                    placeholder="000000"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-4 text-center text-2xl font-mono tracking-widest focus:border-[#4ADE80]/50 focus:outline-none">

                <button type="submit" 
                    class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black glow-green">
                    Verify
                </button>
            </form>
        </div>
    </div>
</body>
</html>