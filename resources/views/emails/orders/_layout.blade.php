<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subjectLine ?? $order['store'] }}</title>
</head>
<body style="margin:0;padding:0;background:#f6f1e7;color:#1b1613;font-family:Segoe UI,Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f1e7;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #eadfce;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="background:#1b1613;padding:22px 28px;border-bottom:4px solid #e8b84a;">
                            <img src="{{ $order['logo'] }}" alt="Cranzo" width="48" height="48" style="display:block;border-radius:8px;margin:0 0 12px;">
                            <p style="margin:0;color:#e8b84a;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;">{{ $order['store'] }}</p>
                            <h1 style="margin:8px 0 0;color:#fffdf9;font-size:24px;line-height:1.3;">{{ $heading }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px;font-size:16px;">Hello {{ $order['customer_name'] }},</p>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#3f3832;">{{ $intro }}</p>
                            @include('emails.orders._summary')
                            @if(!empty($order['view_url']) || !empty($order['retry_url']))
                                <p style="margin:22px 0 0;">
                                    @if(!empty($order['retry_url']))
                                        <a href="{{ $order['retry_url'] }}" style="display:inline-block;background:#e8b84a;color:#1b1613;text-decoration:none;font-weight:700;border-radius:999px;padding:12px 20px;margin:0 8px 8px 0;">Retry payment</a>
                                    @endif
                                    @if(!empty($order['view_url']))
                                        <a href="{{ $order['view_url'] }}" style="display:inline-block;background:#1b1613;color:#fffdf9;text-decoration:none;font-weight:700;border-radius:999px;padding:12px 20px;">View order</a>
                                    @endif
                                </p>
                            @endif
                            @if($order['support_email'])
                                <p style="margin:22px 0 0;font-size:14px;line-height:1.6;color:#3f3832;">Questions about this order can be sent to <a href="mailto:{{ $order['support_email'] }}" style="color:#8a6a24;">{{ $order['support_email'] }}</a>.</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;background:#fffdf9;border-top:1px solid #eadfce;">
                            <p style="margin:0;font-size:12px;line-height:1.5;color:#5c534c;">{{ $order['store'] }} · {{ $order['year'] }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
