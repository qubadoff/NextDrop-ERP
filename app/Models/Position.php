<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $guarded = ['id'];

    public function Department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
