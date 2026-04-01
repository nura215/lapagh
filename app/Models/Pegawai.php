<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'status_pegawai',
        'jabatan',
        'foto_profile',
    ];

    protected $appends = [
        'foto_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_profile
            ? Storage::disk('public')->url($this->foto_profile)
            : null;
    }
}
