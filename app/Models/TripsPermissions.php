<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripsPermissions extends Model
{
    protected $table = 'trips_permissions';
    protected $primaryKey = 'permission_id';
    protected $fillable = [
        'name'
    ];
    public $timestamps = false;


    public function users()
    {
        return $this->hasMany(User::class, 'permission_id');
    }
    public function sharedTrips()
    {
        return $this->hasMany(SharedTrip::class, 'permission_id');
    }
}
