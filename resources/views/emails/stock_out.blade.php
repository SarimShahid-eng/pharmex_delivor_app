@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">Stocks Out</h1>
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
                                <th>Batch<br> Code</th>
                                <th>Entry date</th>
                                <th>Entry By</th>
                                <th>Locations<br> Name</th>
                               
                                <th>Item Code</th>
                                <th>Item Name</th> 
                                
                                <th>Qty</th>
                                <th>Site</th>
                                <th>Pallet Name</th>
                                <th>Expiry Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stock_data as $k => $stock_data)
                            <tr>  
                              <th>{{ $k+1 }}</th>    
                                <th>{{  CommonHelpers::batchCodeDate($stock_data->batch_code) }}</th>
                                <th>@php  $entry_date = CommonHelpers::date_time_full($stock_data->created_at);
                                   echo wordwrap($entry_date, 14, "<br>\n"); @endphp</th>    
                              <th> @php echo wordwrap($stock_data->added_by->full_name, 8, "<br>\n") @endphp</th>    
                                <th>{{ $stock_data->locations->location_name }}</th>    
                                <th>{{ $stock_data->item_code_stock }}</th>
                                <th> @php echo wordwrap($stock_data->items->item_name, 16, "<br>\n") @endphp</th>    
                                <th>{{ $stock_data->qty }}</th>
                                <th>{{ $stock_data->locations->site }}</th>    
                                <th>{{ @(@$stock_data->pallets->pallet_name)?@$stock_data->pallets->pallet_name:'Non-Pallet' }}</th>
                                <th><?php echo CommonHelpers::date_format_custom($stock_data->expiry_date); ?></th>    
                                <th>{{ $stock_data->description }}</th>
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