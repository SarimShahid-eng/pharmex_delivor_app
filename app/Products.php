<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'products';
     protected $casts = [
        'is_bonus' => 'boolean',
    ];

    public function Companies()
    {
        return $this->belongsTo('App\Companies', 'company_id');
    }
    public function company_group()
    {
    	return $this->belongsTo('App\Companies','sub_company_id');
    }

    public function company_target()
    {
        return $this->belongsTo('App\CompanyTask','company_id');
    }
}
