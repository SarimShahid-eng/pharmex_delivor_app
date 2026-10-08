<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'policies';

    protected $casts = [
        'is_active' => 'boolean',
        'policies' => 'array',   // auto JSON encode/decode
    ];
}
