<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LaporanBukti extends Model
{
    use HasFactory;

    protected $table = 'laporan_bukti';

    protected $fillable = [
        'laporan_id',
        'file_path',
    ];

    protected $appends = [
        'url',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }
}
