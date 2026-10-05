@extends('emails.master')
@section('content')
<tr>
    <td bgcolor="#ff511d" align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 3px; line-height: 48px;">
                    <h1 style="font-size: 32px; font-weight: 400; margin: 0;">All Items</h1>
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
                                <th>Item<br> Name</th>
                                <th>Category</th>
                                <th>Unit<br> Size</th>
                                <th>CTN <br>Size</th>
                                <th>CTN <br>Price</th>
                                <th>Stock <br>Level</th>
                                <th>Per Level</th>
                                <th>Taxable</th>
                                <th>Groups</th>
                                <th>Unit Name</th>
                                <th>Unit price</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item_rec as $k => $item_data)
                            <tr>    
                                <td>{{ $k+1 }}</td>
                                <td>{{ $item_data->item_code }}</td>
                                <td><?php echo wordwrap($item_data->item_name, 25, "<br>\n"); ?></td>
                                <td>{{ $item_data->category_name }}</td>
                                <td>{{ $item_data->unit_size }}</td>
                                <td>{{ $item_data->ctn_size }}</td>
                                <td>{{ $item_data->ctn_cost  }}</td>
                                <td>
                                    <?php $stock = $item_data->stock_in - $item_data->stock_out;
                                        if ($stock < $item_data->par_level) {
                                            $stock_level = 'Low';
                                        } else {
                                            $stock_level = 'High';
                                        } echo $stock_level;
                                    ?>
                                </td>
                                <td>{{ $item_data->par_level  }}</td>
                                <td>{{ $item_data->taxable }}</td>
                                <td>{{ $item_data->groups }}</td>
                                <td>{{ $item_data->unit_name }}</td>
                                <td>{{ $item_data->unit_cost }}</td>
                                <td>{{ $item_data->description  }}</td>    
                            </tr>
                             @endforeach
                            <tr>
                                <td></td>
                                <td></td>     
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>     
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>     
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection