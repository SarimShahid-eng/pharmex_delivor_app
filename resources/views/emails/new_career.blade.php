@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">New Career Submitted</h1>
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
                    <p style="margin: 15px 0;">We have received a following details for the newly submitted career on Gexton INC Site.</p>
                </td>
            </tr>

            <tr>
                <td bgcolor="#ffffff" align="left" style="padding:0 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;">
                    <table width="100%" border="1" cellspacing="0" cellpadding="8">
                        <tr>
                            <th style="text-align: left">Name:</th>
                            <td>{{ $data['fullname'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Job Title:</th>
                            <td>{{ $data['job_title'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Martial Status:</th>
                            <td>{{ $data['is_married'] ? 'Married' : 'Single' }}</td>
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
                            <th style="text-align: left">Nationality:</th>
                            <td>{{ $data['nationality'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">City:</th>
                            <td>{{ $data['city'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Address:</th>
                            <td>{{ $data['address'] }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Religion:</th>
                            <td>{{ $data['religion'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- BULLETPROOF BUTTON -->
            <tr>
                <td bgcolor="#ffffff" align="left">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td bgcolor="#ffffff" align="center" style="padding: 30px 30px 20px 30px;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="border-radius: 3px;" bgcolor="#ff6213"><a href="{{ check_file($data['cv']) }}" target="_blank" style="font-size: 18px; font-family: Helvetica, Arial, sans-serif; color: #ffffff !important; text-decoration: none; color: #ffffff; text-decoration: none; padding: 10px 25px; border-radius: 2px; border: 1px solid #ff6213; display: inline-block;">View CV</a></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection