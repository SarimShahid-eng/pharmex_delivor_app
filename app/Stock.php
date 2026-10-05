<?php

namespace App;
use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{   
    use DianujHashidsTrait;
    protected $table = 'stocks';
    public $timestamps = false;
    
    public function parentProduct(){
        return $this->belongsTo('App\Products','product_id');
    }
}
