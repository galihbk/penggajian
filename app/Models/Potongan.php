<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Potongan extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jumlah',
        'nama_potongan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
