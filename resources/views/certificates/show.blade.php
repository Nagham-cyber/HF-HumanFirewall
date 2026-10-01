@extends('layouts.app')

@section('title', 'Certificate')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <div class="relative rounded-3xl shadow-2xl overflow-hidden" id="certificate" style="box-shadow: 0 0 60px rgba(74,222,128,0.15);">
        <img src="{{ asset('pic/cer1.jpg') }}" alt="Certificate" class="w-full h-auto" crossorigin="anonymous">
        <div class="absolute inset-0 flex flex-col items-center px-12" style="padding-top: 28.5%;">
            <h1 class="font-extrabold text-black text-center" style="font-family: 'Georgia', serif; margin-bottom: 1.5%; font-size: 14px;">{{ $user->name }}</h1>
            <p class="text-black/80 text-center" style="margin-bottom: 1%; font-size: 9px;">has successfully completed all security scenarios in</p>
            <p class="font-black text-black text-center" style="margin-bottom: 6.5%; font-size: 11px;">{{ $courseName }}</p>
            <div class="w-full flex justify-center items-end" style="gap: 22%; margin-top: 0.5%;">
                <span class="text-black" style="font-size: 6.8px; font-weight: 900; transform: translate(52px, -16px); font-family: 'Arial Black', sans-serif;">{{ $certificateNumber }}</span>
                <span class="text-black" style="font-size: 6.8px; font-weight: 900; transform: translate(8px, -16px); font-family: 'Arial Black', sans-serif;">{{ \Carbon\Carbon::parse($issueDate)->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-center gap-3 mt-6">
        <a href="{{ asset('pic/cer1.jpg') }}" download="HF-{{ $certificateNumber }}.jpg" class="px-5 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm glow-green">
            <span class="material-icons text-base align-middle mr-1">download</span> JPG
        </a>
        <a href="/certificates/{{ $type }}/download-pdf" class="px-5 py-3 bg-red-600 text-white rounded-full font-bold text-sm">
            <span class="material-icons text-base align-middle mr-1">picture_as_pdf</span> PDF
        </a>
        <button onclick="shareLinkedIn()" class="px-5 py-3 bg-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-sm">
            <span class="material-icons text-base align-middle mr-1">share</span> LinkedIn
        </button>
        <a href="{{ route('certificates.index') }}" class="px-5 py-3 glass text-[#94A3B8] rounded-full font-bold text-sm">
            <span class="material-icons text-base align-middle mr-1">arrow_back</span> Back
        </a>
    </div>
</div>

<script>
function shareLinkedIn() {
    const text = '🎓 I earned my {{ $courseName }} Certificate from HF!\nCertificate ID: {{ $certificateNumber }}\n#HumanFirewall #Cybersecurity';
    window.open('https://www.linkedin.com/sharing/share-offsite/?text=' + encodeURIComponent(text), '_blank', 'width=700,height=600');
}
</script>
@endsection