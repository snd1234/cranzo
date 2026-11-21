<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>We received your message</title>
</head>
<body style="margin:0;padding:0;background:#f6f1e7;font-family:Segoe UI,Arial,sans-serif;color:#1b1613;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f1e7;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border:1px solid #e8b84a;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="background:#1b1613;padding:22px 28px;border-bottom:4px solid #e8b84a;">
                            <p style="margin:0;color:#e8b84a;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;">Cranzo</p>
                            <h1 style="margin:8px 0 0;color:#fffdf9;font-size:26px;line-height:1.2;">Thank you</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px;font-size:16px;">Hello {{ $name }},</p>
                            <p style="margin:0 0 12px;font-size:15px;line-height:1.6;color:#3f3832;">We have received your message and will reply to <strong>{{ $email }}</strong>.</p>
                            @if($subject !== '')
                                <p style="margin:0 0 8px;font-size:15px;"><strong>Subject</strong><br>{{ $subject }}</p>
                            @endif
                            <p style="margin:16px 0 0;font-size:15px;line-height:1.6;white-space:pre-wrap;">{{ $message }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;background:#fffdf9;border-top:1px solid #eadfce;">
                            <p style="margin:0;font-size:12px;color:#5c534c;">This note was sent because a message was submitted on the Cranzo contact page.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
