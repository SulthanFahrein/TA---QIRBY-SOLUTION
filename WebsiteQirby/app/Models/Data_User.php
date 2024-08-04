<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Data_User extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'data_user';

    protected $guarded = [];

    protected $primaryKey = 'id';
    protected $fillable = ['id', 'name_user', 'phone_user', 'email_user', 'password','profile_image','created_at', 'updated_at'];

    public function setPasswordUserAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

