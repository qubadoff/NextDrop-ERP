<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerGroup extends Model
{
    use HasFactory;

    protected $table = 'owner_groups';

    protected $guarded = [];
}
