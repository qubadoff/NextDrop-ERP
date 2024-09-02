<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecondGroup extends Model
{
    use HasFactory;

    protected $table = 'second_groups';

    protected $guarded = [];

    public function ownerGroup(): BelongsTo
    {
        return $this->belongsTo(OwnerGroup::class);
    }
}
