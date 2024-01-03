<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use Notifiable;
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
    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'user_trips', 'user_id', 'trip_id');
    }
    // Relacja: zaproszenia wysłane przez użytkownika
    public function invitesSent()
    {
        return $this->hasMany(UserInvite::class, 'invited_by');
    }

    // Relacja: zaproszenia otrzymane przez użytkownika
    public function invitesReceived()
    {
        return $this->hasMany(UserInvite::class, 'user_id');
    }
    public function sharedTrips()
    {
        return $this->hasMany(SharedTrip::class, 'user_id');
    }
}
