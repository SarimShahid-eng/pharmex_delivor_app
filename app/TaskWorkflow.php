<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskWorkflow extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'task_workflow';
    
    
  public function Regions()
{
    return $this->belongsToMany(
        Regions::class,             // Related model
        'regions_task_workflow',     // Pivot table name
        'task_workflow_id',         // Foreign key for this model in pivot
        'region_id'                 // Foreign key for related model in pivot
    );
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
        return $this->hasMany('App\CompanyTaskWorkflow','task_workflow_id');
    }

}
