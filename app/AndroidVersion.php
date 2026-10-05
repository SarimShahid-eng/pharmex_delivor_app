<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;
class AndroidVersion extends Model
{   
    use DianujHashidsTrait;
    protected $table = 'android_versions';
}
