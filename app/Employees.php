<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'employee';
    protected $appends = ['simple_password'];

    public function getSimplePasswordAttribute(){
        $pass = base64_decode($this->password);
        $pass1 = base64_decode($pass);
        return $pass1;
    }

    public function UserDetails()
    {
    	return $this->belongsTo('App\Users', 'u_id');
    }
}
