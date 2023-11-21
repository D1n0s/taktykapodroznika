<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{

    protected $primaryKey = 'mark_id';

    protected $fillable = [
        'trip_id',
        'name',
        'desc',
        'address',
        'latitude',
        'longitude',
        'category_id',
        'is_general',
        'created_at',
        'updated_at',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
    public function attractions()
    {
        return $this->hasMany(Attraction::class, 'mark_id', 'mark_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categorie_marks', 'mark_id', 'category_id');
    }

}
