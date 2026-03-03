<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #FF6B35; margin: 0;">{{ \App\Models\FoundationSetting::value('name') ?? 'Yayasan Peduli' }}</h2>
        </div>
        
        <p style="color: #333; font-size: 16px; line-height: 1.5;">Halo,</p>
        <p style="color: #333; font-size: 16px; line-height: 1.5;">Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <div style="display: inline-block; background-color: #FFF0EB; border: 2px solid #FF6B35; color: #FF6B35; font-size: 24px; font-weight: bold; padding: 10px 30px; letter-spacing: 5px; border-radius: 8px;">
                {{ $otp }}
            </div>
        </div>
        
        <p style="color: #666; font-size: 14px; line-height: 1.5;">Kode ini hanya berlaku selama 15 menit. Jangan berikan kode ini kepada siapa pun.</p>
        <p style="color: #666; font-size: 14px; line-height: 1.5;">Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
        
        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
            <p style="color: #999; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} {{ \App\Models\FoundationSetting::value('name') ?? 'Yayasan Peduli' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
