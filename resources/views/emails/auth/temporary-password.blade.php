<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Veltrix Temporary Password</title>
</head>
<body style="margin:0; background:#f5efe4; color:#171717; font-family:Arial, sans-serif;">
    <div style="max-width:640px; margin:0 auto; padding:32px 20px;">
        <div style="background:#ffffff; border:2px solid #171717; border-radius:24px; padding:32px;">
            <h1 style="margin:0 0 20px; font-size:28px; line-height:1.2;">Veltrix Temporary Password</h1>

            <p style="margin:0 0 16px; font-size:16px; line-height:1.6;">
                Hi {{ $user->name ?: 'there' }},
            </p>

            <p style="margin:0 0 16px; font-size:16px; line-height:1.6;">
                We generated a temporary password for your Veltrix account.
            </p>

            <div style="margin:24px 0; padding:20px; background:#f8f8f8; border:2px dashed #171717; border-radius:18px; text-align:center;">
                <p style="margin:0 0 8px; font-size:14px; line-height:1.5; color:#5f5f5f;">Temporary password</p>
                <p style="margin:0; font-size:30px; line-height:1.2; font-weight:700; letter-spacing:1px;">{{ $temporaryPassword }}</p>
            </div>

            <p style="margin:0 0 16px; font-size:16px; line-height:1.6;">
                Use this password to sign in, then go to your profile settings to change it to something personal.
            </p>

            <p style="margin:0; font-size:16px; line-height:1.6;">
                If you did not request this, please sign in and change your password straight away.
            </p>
        </div>
    </div>
</body>
</html>
