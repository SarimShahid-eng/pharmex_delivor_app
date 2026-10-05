<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Items extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'items';

    public function categories()
    {
        return $this->belongsTo('App\Categories', 'categories_id');
    }
}
