@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">Stock Expiry</h1>
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
                                <th>S.No</th>
                                <th>Item<br>Code</th>
                                <th>Item<br>Name</th>
                                <th>Expiry<br>Date</th>
                                <th>Expiry <br>Remainings Days</th>
                                <th>Category</br>Name</th>
                                <th>Location</br>Name</th>
                                <th>Site</th>
                                <th>Pallet</br>Name</th>
                                <th><b>Stock<br> Available</b></th>
                                <th>Unit<br> Size</th>
                                <th>CTN <br>Size</th>
                                <th>CTN <br>Price</th>
                                <th>Stock <br>Value</th>
                                
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($stocks as $k => $stock_data)
                            <tr>  
                                <th>{{ $k+1 }}</th>
                                <th>{{ $stock_data['item_code_stock'] }}</th>    
                                <th>{{ $stock_data['item_name'] }}</th>    
                                <th>{{ $stock_data['expiry_date'] }}</th>
                                 <td>{{ $stock_data['remainings_days'] }} </td>
                                <th>{{ $stock_data['category_name'] }}</th>  
                                <th>{{ $stock_data['location_name'] }}</th>
                                <th>{{ $stock_data['site'] }}</th>
                                <th>{{ @$stock_data['pallet_name'] }}</th>
                                <th>{{ $stock_data['qty'] }}</th>
                                <td>{{ $stock_data['unit_size'] }}</td>
                                <td>{{ $stock_data['ctn_size'] }}</td>
                                <td>{{ $stock_data['unit_cost'] }}</td>
                                <td>
                                    @php
                                    $stock_available = $stock_data['qty'];
                                    $stock_value = $stock_data['ctn_cost'] * $stock_available;
                                    echo number_format($stock_value);
                                    @endphp
                                </td>
                               
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