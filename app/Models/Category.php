<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
    ];

    public function marks()
    {
        return $this->belongsToMany(Mark::class, 'categorie_marks', 'category_id', 'mark_id');
    }
}
