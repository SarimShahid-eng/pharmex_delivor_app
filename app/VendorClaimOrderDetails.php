<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorClaimOrderDetails extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $table = 'vendor_claim_order_details';

    public function childProduct(){
        return $this->belongsTo('App\Products','item_id');
    }
}
