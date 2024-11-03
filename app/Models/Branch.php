<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function ($branch) {
            $qrContent = Str::uuid();
            $qrCodePath = 'public/qrcodes/' . $qrContent . '.png';

            // QR kodunu storage/app/public/qrcodes dizinine kaydet
            QrCode::format('png')->size(200)->generate($qrContent, storage_path('app/' . $qrCodePath));

            // Kayıt sırasında sadece 'qrcodes/...' kısmını saklayın
            $branch->qr_code_path = 'qrcodes/' . $qrContent . '.png';
        });
    }

}
