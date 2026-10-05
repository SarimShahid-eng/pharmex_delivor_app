<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable, DianujHashidsTrait, SoftDeletes;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'firstname', 'lastname', 'image', 'user_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'rights' => 'object',
    ];

    public function getFullNameAttribute()
    {
        return ucwords($this->firstname . ' ' . $this->lastname);
    }

  
    public function added_by(){
        return $this->belongsTo('App\Admin', 'added_by_id');
    }

    public function scopeAgents($query){
        return $query->where('user_role', 'agent');
    }

    public function getIsAdminAttribute()
    {
        return $this->user_role == 'admin';
    }

    public function getIsManagerAttribute()
    {
        return $this->user_role == 'manager';
    }

    public function getIsBookerAttribute()
    {
        return $this->user_role == 'booker';
    }

    public function getIsAgentAttribute()
    {
        return $this->user_role == 'agent';
    }
}
