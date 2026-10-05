@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">All Categories</h1>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!-- COPY BLOCK -->
<tr>
    <td bgcolor="#eeeeee" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <!-- COPY -->
            <tr>
                <td bgcolor="#ffffff" align="left" style="padding:0 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;">
                    <table width="100%" border="1" cellspacing="0" cellpadding="8">
                        <thead>
                            <tr>
                                <th width="30">S.No</th>
                                <th>Categories Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cat_data as $k => $cat_data)
                            <tr>    
                                <td>{{ $k+1 }}</td>
                                <td>{{ $cat_data->category_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection