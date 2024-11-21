<?php

namespace App\Models;

use Exception;
use Filament\Notifications\Notification;
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
                Notification::make()
                    ->title('Silme Başarısız')
                    ->danger()
                    ->body('Bu pozisyona bağlı çalışanlar olduğu üçün silinemez.')
                    ->send();

                return false;
            }
        });
    }


    public function Department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
