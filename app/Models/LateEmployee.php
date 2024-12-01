<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateEmployee extends Model
{
    protected $table = 'late_employees';

    protected $guarded = ['id'];
}
