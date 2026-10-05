<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'tasks';

    public function Regions()
    {
    	return $this->belongsToMany(Regions::class);
    }

    public function Groups()
    {
        return $this->belongsTo('App\Groups', 'groups_id');
    }

    public function Employees()
    {
        return $this->belongsTo('App\Employees', 'employee_id');
    }

    public function company_task(){
        return $this->hasMany('App\CompanyTask','task_id');
    }

    
    
}
