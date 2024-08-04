<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Property extends Model
{
    use HasFactory, HasSpatial;

    protected $table = 'property';

    protected $fillable = ['name','price','status','address',
                            'description','sqft','bath',
                            'garage','floor', 'bed', 'koordinat']; 

                            protected $casts = [
                                'koordinat' => Point::class
                            ];
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
