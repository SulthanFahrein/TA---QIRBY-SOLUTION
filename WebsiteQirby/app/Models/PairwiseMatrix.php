<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairwiseMatrix extends Model
{
    use HasFactory;

    protected $fillable = ['criterion_name', 'matrix'];
}
