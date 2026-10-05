<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'account_types';

    public function branch(){
    	return $this->belongsTo('App\Branch', 'branch_id');
    }

    public function added_by(){
        return $this->belongsTo('App\Admin', 'added_by_id');
    }
}
