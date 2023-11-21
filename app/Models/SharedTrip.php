<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedTrip extends Model
{
    protected $primaryKey = 'shared_id';
    public $timestamps = false;

    protected $fillable = [
        'trip_id',
        'user_id',
        'permission_id',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function permission()
    {
        return $this->belongsTo(TripsPermissions::class, 'permission_id');
    }
}
