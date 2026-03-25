<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .btn { display: inline-block; padding: 12px 24px; background: #4F46E5; color: #fff; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, {{ $user->name }}!</h2>
        <p>Thanks for registering at <strong>{{ config('app.name') }}</strong>.</p>
        <p>We are excited to have you on board. Your account is now active and ready to use.</p>
        <p>Explore your dashboard, customize your profile, and get started with our features.</p>

        <p>If you need any help, our support team is here for you.</p>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>