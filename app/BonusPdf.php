<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BonusPdf extends Model
{   
    use SoftDeletes;

    protected $table = 'bonus_pdfs';
}
