@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">New Contact Submitted</h1>
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
                    <p style="margin: 15px 0;">We have received a following details for the newly submitted contact on Gexton INC Site.</p>
                </td>
            </tr>

            <tr>
                <td bgcolor="#ffffff" align="left" style="padding:0 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px; padding-bottom: 60px;">
                    <table width="100%" border="1" cellspacing="0" cellpadding="8">
                        <tr>
                            <th style="text-align: left">Name:</th>
                            <td>{{ $data['name'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Email:</th>
                            <td>{{ $data['email'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Phone:</th>
                            <td>{{ $data['phone'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Message:</th>
                            <td>{{ $data['msg'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection