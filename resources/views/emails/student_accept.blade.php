<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6fb;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 600px;
            margin: 40px auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 32px;
        }
        .header {
            text-align: center;
            padding-bottom: 16px;
        }
        .header img {
            width: 80px;
        }
        .title {
            color: #2d6cdf;
            font-size: 28px;
            margin-bottom: 8px;
        }
        .content {
            font-size: 16px;
            color: #333;
            line-height: 1.7;
        }
        .button {
            display: inline-block;
            margin-top: 24px;
            padding: 12px 32px;
            background: #2d6cdf;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            color: #888;
            font-size: 13px;
            margin-top: 32px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/ucs_logo.png') }}" alt="UCS Hinthada Logo">
            <div class="title">Welcome to UCS Hinthada!</div>
        </div>
        <div class="content">
            <p>Dear {{ $reg->User->name }},</p>
            <p>
                Congratulations! Your registration has been <strong> successfully completed</strong>.<br>
                An now you are ready to attend to {{ $reg->academicYear->name }}.
            </p>
            <p>
                You can now access your student portal to view your profile, courses, and important updates.
            </p>
            <p style="margin-top: 32px;">
                If you have any questions, feel free to contact our support team.<br>
                We wish you a wonderful journey ahead!
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} UCS Hinthada. All rights reserved.
        </div>
    </div>
</body>
</html>