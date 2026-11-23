<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background-color:#ffffff;border-radius:8px;padding:32px;">
                    <tr>
                        <td style="text-align:center;padding-bottom:16px;">
                            <h2 style="margin:0;color:#111827;font-size:22px;">QJobs</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:8px;">
                            <p style="margin:0;color:#374151;font-size:15px;">Hi {{ isset($user->name) ? $user->name : 'there' }},</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:16px;">
                            <p style="margin:0;color:#4b5563;font-size:14px;line-height:22px;">
                                A password reset was requested for your account. Your new temporary password is:
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom:24px;">
                            <p style="display:inline-block;background-color:#fde047;color:#111827;font-family:monospace;font-weight:bold;font-size:18px;letter-spacing:2px;padding:12px 28px;border-radius:8px;margin:0;">{{ $newPassword }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:24px;">
                            <p style="margin:0;color:#4b5563;font-size:14px;line-height:22px;">
                                Please sign in with this password and change it from your profile as soon as possible.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #e5e7eb;padding-top:16px;">
                            <p style="margin:0;color:#9ca3af;font-size:12px;">
                                If you did not request a password reset, please contact support immediately — your previous password is no longer valid.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
