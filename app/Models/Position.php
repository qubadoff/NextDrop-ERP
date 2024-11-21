<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Position $position) {
            if (Employee::where('position_id', $position->id)->exists()) {
                throw new Exception('Bu pozisyona bağlı çalışanlar olduğu üçün silinemez.');
            }
        });
    }


    public function Department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
