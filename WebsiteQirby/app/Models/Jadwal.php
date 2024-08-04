<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    public $timestamps = false;

    protected $primaryKey = 'id_jadwal';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Data_User::class, 'id_pengguna');
    }

    public function properti(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'id_properti');
    }

    protected $fillable = [
        'tanggal',
        'pukul',
        
    ];
}

