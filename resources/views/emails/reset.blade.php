@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">Reset Password</h1>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!-- COPY BLOCK -->
<tr>
    <td bgcolor="#eeeeee" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <!-- COPY -->
            <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 0px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                    <p style="margin: 0;"><strong>Hello, </strong></p>
                    <p style="margin: 15px 0;">We have received a password reset request for your account. </p>
                </td>
            </tr>
            <!-- BULLETPROOF BUTTON -->
            <tr>
                <td bgcolor="#ffffff" align="left">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td bgcolor="#ffffff" align="center" style="padding: 10px 30px 20px 30px;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="border-radius: 3px;" bgcolor="#ff6213"><a href="{!! route('password.reset', ['token' => $email_data['token']]) !!}" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff !important;; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #ff6213; display: inline-block;">Click Here To Reset</a></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                    <p style="margin: 15px 0;">If you did not request a password reset, no further action is required.</p>
                    <p style="margin: 5px 0;">If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
                    <p style="font-size: 14px; margin: 10px 0; word-break: break-word;">
                        <a style="color: #ff511d;" href="{!! route('password.reset', ['token' => $email_data['token']]) !!}">{!! route('password.reset', ['token' => $email_data['token']]) !!}</a>
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection

@section('browser-view')
<p style=" margin: 0;">You received this email because we received a password reset request for your account. If it looks weird, <a href="{!! route('front.email.view', [$email_data['email_id']]) !!}" target="_blank" style="color: #111111; font-weight: 700;">view it in your browser</a>.</p>
@endsection