<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $guarded = [];

    public function secondGroup(): BelongsTo
    {
        return $this->belongsTo(SecondGroup::class);
    }
}
