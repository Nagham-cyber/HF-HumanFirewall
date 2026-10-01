<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'DejaVu Sans', 'Georgia', serif; 
            margin: 0; 
            padding: 0; 
        }
        .certificate {
            width: 100%;
            height: 100vh;
            border: 8px solid #f59e0b;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            background: linear-gradient(180deg, #ffffff 0%, #fef3c7 50%, #ffffff 100%);
            box-sizing: border-box;
        }
        .logo { 
            color: #3b82f6; 
            font-size: 28px; 
            font-weight: bold; 
            margin-bottom: 5px;
        }
        .title { 
            font-size: 14px; 
            color: #6b7280; 
            text-transform: uppercase; 
            letter-spacing: 3px;
            margin-bottom: 20px;
        }
        .seal { 
            font-size: 50px; 
            margin: 15px 0; 
        }
        .label { 
            font-size: 12px; 
            color: #6b7280; 
        }
        .name { 
            font-size: 36px; 
            font-weight: bold; 
            color: #111827; 
            margin: 10px 0;
            font-family: 'Georgia', serif;
        }
        .divider { 
            width: 200px; 
            height: 2px; 
            background: linear-gradient(to right, #3b82f6, #10b981); 
            margin: 15px auto; 
        }
        .course { 
            font-size: 20px; 
            color: #3b82f6; 
            font-weight: bold; 
        }
        .info-container {
            display: inline-block;
            margin: 30px 15px;
            padding: 10px 20px;
            background: #f9fafb;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }
        .info-label { 
            font-size: 9px; 
            color: #6b7280; 
            text-transform: uppercase;
        }
        .info-value { 
            font-size: 14px; 
            font-weight: bold; 
            color: #111827;
        }
        .signature-area {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding: 0 80px;
        }
        .signature {
            width: 180px;
            border-bottom: 1px solid #6b7280;
        }
        .signature-label {
            font-size: 10px;
            color: #6b7280;
            margin-top: 5px;
        }
        .stamp {
            width: 70px;
            height: 70px;
            border: 3px solid #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="logo">HF - Human Firewall</div>
        <div class="title">Certificate of Completion</div>
        <div class="seal">🎓</div>
        
        <p class="label">This is to certify that</p>
        <div class="name">{{ $user->name }}</div>
        <div class="divider"></div>
        
        <p class="label">has successfully completed all security scenarios in</p>
        <div class="course">{{ $courseName }}</div>
        
        <div>
            <div class="info-container">
                <div class="info-label">Certificate ID</div>
                <div class="info-value">{{ $certificateNumber }}</div>
            </div>
            <div class="info-container">
                <div class="info-label">Issue Date</div>
                <div class="info-value">{{ $issueDate }}</div>
            </div>
        </div>
        
        <div class="signature-area">
            <div>
                <div class="signature"></div>
                <div class="signature-label">Security Director</div>
            </div>
            <div class="stamp">🏅</div>
        </div>
        
        <div class="footer">Verify this certificate at sentinelplay.com | Certificate ID: {{ $certificateNumber }}</div>
    </div>
</body>
</html>