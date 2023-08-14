<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'login',
        'name',
        'surname',
        'phone',
        'email',
        'email_verified_at',
        'remember_token',
        'password',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function settings()
    {
        return $this->hasOne(UserSettings::class, 'user_id');
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'user_trips', 'user_id', 'trip_id');
    }
}
