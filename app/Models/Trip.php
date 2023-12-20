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
        'start_date',
        'end_date',
        'travel_time',
        'distance',
        'avg_speed',
        'fuel_consumed',
        'travel_cost',
        'persons',
        'petrol_cost',
        'diesel_cost',
        'gas_cost',
    ];

    public function marks()
    {
        return $this->hasMany(Mark::class, 'trip_id');
    }
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'trip_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sharedusers()
    {
        return $this->belongsToMany(User::class, 'shared_trips', 'trip_id', 'user_id')->withPivot('permission_id');
    }
    public function sharedUsersPermissions()
    {
        return $this->belongsToMany(User::class, 'shared_trips', 'trip_id', 'user_id')
            ->withPivot('permission_id');
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
