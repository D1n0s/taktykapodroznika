<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $primaryKey = 'post_id';
    public $timestamps = false;

    protected $fillable = [
        'trip_id',
        'title',
        'desc',
        'day',
        'date',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }
    public function attractions()
    {
        return $this->hasMany(Attraction::class, 'post_id', 'post_id');
    }
}
