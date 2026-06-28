<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>بوابة الأبطال والبراعم — منصة آل السيحي</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=Amiri:wght@700&display=swap" rel="stylesheet"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background: radial-gradient(circle at 50% 50%, #f0fdf4 0%, #ecfdf5 50%, #dbeafe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .child-login-card {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 32px;
            box-shadow: 0 20px 40px rgba(6, 78, 59, 0.12), 0 1px 3px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            text-align: center;
            border: 3px dashed #059669; 
            position: relative;
        }

       

        @keyframes rotateStar { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* الدائرة الخلفية للأيقونة */
        .child-icon-badge {
            width: 95px; height: 95px;
            background: linear-gradient(135deg, #f59e0b, #d97706); 
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px auto;
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.3);
            border: 4px solid #fff;
            animation: floatLogo 3.5s ease-in-out infinite;
            overflow: hidden; /* لمنع خروج الصورة عن الإطار الدائري */
            padding: 10px; /* مسافة جمالية داخل الدائرة */
        }

        /* تنسيق صورة الأطفال الجديدة kids.png */
        .child-icon-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-8px) scale(1.03); }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .child-title { 
            font-family: 'Amiri', serif;
            font-size: 28px; font-weight: 900; 
            color: #064e3b; 
            margin-bottom: 8px; 
        }
        .child-title span { color: #d97706; } 
        
        .child-subtitle { 
            font-size: 14px; color: #4b5563; 
            margin-bottom: 30px; font-weight: 500; 
            background: #f1f5f9; padding: 6px 12px; border-radius: 20px; display: inline-block;
        }
        
        .form-group { text-align: right; margin-bottom: 22px; }
        .form-label { display: block; font-size: 13.5px; font-weight: 700; color: #064e3b; margin-bottom: 8px; }
        
        .form-input {
            width: 100%; padding: 14px 18px; 
            border: 2.5px solid #cbd5e1; border-radius: 16px;
            font-family: 'Tajawal'; font-size: 15px; font-weight: 500;
            outline: none; transition: all 0.25s; text-align: center;
            background-color: #fcfcfc;
        }
        .form-input:focus { 
            border-color: #059669; 
            background-color: #fff;
            box-shadow: 0 0 0 5px rgba(5, 150, 105, 0.15); 
        }
        
        .form-input.pin { 
            letter-spacing: 12px; font-size: 24px; font-weight: 900; 
            color: #d97706; background-color: #fffde6; border-color: #fef08a;
        }
        .form-input.pin:focus { border-color: #f59e0b; box-shadow: 0 0 0 5px rgba(245, 158, 11, 0.2); }

        .btn-child-submit {
            width: 100%; padding: 16px; 
            background: linear-gradient(135deg, #10b981, #059669); 
            color: #fff; font-family: 'Tajawal'; font-size: 17px; font-weight: 800; border: none;
            border-radius: 16px; cursor: pointer; 
            box-shadow: 0 8px 24px rgba(5, 150, 105, 0.35);
            margin-top: 15px; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-child-submit:hover { 
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 12px 28px rgba(5, 150, 105, 0.45);
        }
        .btn-child-submit:active { transform: translateY(-1px); }

        .back-link { 
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 25px; font-size: 13px; color: #6b7280; 
            text-decoration: none; font-weight: 700; transition: color 0.2s; 
        }
        .back-link:hover { color: #064e3b; text-decoration: underline; }
        
        .alert-danger { 
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; 
            padding: 12px 16px; border-radius: 14px; font-size: 13px; 
            margin-bottom: 22px; text-align: right; line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="child-login-card">
    
    <div class="child-icon-badge">
        <img src="{{ asset('images/kids.png') }}" alt="أيقونة الأطفال والبراعم" class="child-icon-img" />
    </div>
    
    <h1 class="child-title">بوابة الأبطال <span>الصغار</span></h1>
    <p class="child-subtitle">أدخل بياناتك الذكية للانطلاق لوردك اليومي </p>

    {{-- عرض الأخطاء إن وجدت --}}
    @if($errors->any())
        <div class="alert-danger">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('child.login.submit') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">✍️ اسم المستخدم الخاص بالطفل:</label>
            <input type="text" name="username" class="form-input" placeholder="مثال: ahmed2026" dir="ltr" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">🔑 رمز الدخول السري PIN (4 أرقام):</label>
            <input type="password" name="pin_code" maxlength="4" pattern="\d{4}" class="form-input pin" placeholder="••••" dir="ltr" required>
        </div>

        <button type="submit" class="btn-child-submit">
            <span>انطلق في رحلتك القرآنية </span>
        </button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
        ← العودة لتسجيل دخول الكبار 
    </a>
</div>

</body>
</html>