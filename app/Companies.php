<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companies extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'companies';

    public function parent()
    {
    	 return $this->hasMany('App\Companies', 'parent_id');
    }

    public function Groups()
    {
        return $this->belongsToMany(Groups::class);
    }

    public function products()
    {
         return $this->hasMany('App\Products', 'vendor_id');
    }
}
