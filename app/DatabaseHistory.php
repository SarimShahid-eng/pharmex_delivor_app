<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatabaseHistory extends Model {

    use DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'database_history';

     public function added_by(){
        return $this->belongsTo('App\Admin', 'user_id');
    }

}
