<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorClaimOrder extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $table = 'vendor_claim_orders';

    public function childCompany(){
        return $this->belongsTo('App\Companies','company_id');
    }
}
