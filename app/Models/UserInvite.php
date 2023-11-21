<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvite extends Model
{
    protected $primaryKey = 'invite_id';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'invited_by',
        'invited_trip',
        'permission',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function invitedTrip()
    {
        return $this->belongsTo(Trip::class, 'invited_trip');
    }
}
