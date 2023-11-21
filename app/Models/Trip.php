<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $primaryKey = 'trip_id';
    public $timestamps = false;
    protected $fillable = [
        'owner_id',
        'title',
        'desc',
        'start_date',
        'end_date',
    ];

    public function marks()
    {
        return $this->hasMany(Mark::class, 'trip_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_trips', 'trip_id', 'user_id');
    }

    public function sharedWithUsers()
    {
        return $this->belongsToMany(User::class, 'shared_trips', 'trip_id', 'shared_with_user_id');
    }

    public function publicTrip()
    {
        return $this->hasOne(PublicTrip::class, 'trip_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'trip_id');
    }
    // Relacja: zaproszenia związane z wyjazdem
    public function invites()
    {
        return $this->hasMany(UserInvite::class, 'invited_trip');
    }
}
