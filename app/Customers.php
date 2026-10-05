<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'customers';

    public function regions()
    {
        return $this->belongsTo('App\Regions', 'region_id');
    }
    public function town()
    {
        return $this->belongsTo('App\Town', 'town_id');
    }
    
}
