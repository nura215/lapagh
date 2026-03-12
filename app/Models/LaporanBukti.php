<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanBukti extends Model
{
    use HasFactory;

    protected $table = 'laporan_bukti';

    protected $fillable = [
        'laporan_id',
        'file_path',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
