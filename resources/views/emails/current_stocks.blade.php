@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">Current Stocks</h1>
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
                                <th>Item <br>Code</th>
                                <th>Category</th>
                                <th>Item Name</th>
                                <th>Location Name</th>
                                <th>pallet Name</th>
                                <th>Expiry <br>Date </th>
                                <th>Unit<br> Size</th>
                                <th>CTN <br>Size</th>
                                <th>CTN <br>Price</th>
                                <th>Stock <br>Value</th>
                                <th>Per Level</th>
                                <th>Stock <br>Avaliable</th>
                                <th>Stock <br>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                              @php $sno = 0; @endphp
                            @foreach($stock_data as $k => $val)
                               @if($val->stock_in_qty > $val->stock_out_qty)
                            @php  
                            $sno++;
                            $stock_available = $val->stock_in_qty - $val->stock_out_qty; 
                            $stock_value = $val->ctn_cost * $stock_available;
                            $stock = $val->stock_in_qty - $val->stock_out_qty;
                            if ($stock < $val->par_level) {
                            $stock_level = 'Low';
                            } else {
                            $stock_level = 'High';
                            }
                            @endphp
                            <tr style=" background-color:{{ ($stock_level == 'Low')?" #eab7b7":"" }}">  
                                <td>{{ $sno }}</td>
                                <th>{{ $val->item_code_stock }}</th>
                                <th>{{ $val->category_name }}</th>
                                <th>@php echo wordwrap($val->item_name, 16, "<br>\n") @endphp</th>
                                <th>@php echo wordwrap($val->location_name, 16, "<br>\n") @endphp</th>
                                <th>@php echo wordwrap($val->pallet_name, 16, "<br>\n") @endphp</th>
                                <th>{{ get_date($val->expiry_date) }}</th>
                                <th>{{ $val->unit_size }}</th>
                                <th>{{ $val->ctn_size }}</th>
                                <th>{{ $val->unit_cost }}</th>
                                <th>{{ $stock_value }}</th>
                                <th>{{ $val->par_level }}</th>
                                <th>{{ $stock_available }}</th>
                                <th>{{ $stock_level }}</th>

                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection