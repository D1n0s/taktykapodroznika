<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTrip extends Model
{
    protected $primaryKey = 'public_id';

    protected $fillable = [
        'trip_id',
        'copied',
        'created_at',
        'updated_at',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
}
