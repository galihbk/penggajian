<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'hadir', 'lembur', 'keterangan'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
