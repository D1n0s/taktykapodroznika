<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieAttraction extends Model
{
    protected $table = 'categorie_attractions';

    protected $primaryKey = 'category_attraction_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'icon',
    ];

    public function attractions()
    {
        return $this->hasMany(Attraction::class, 'category_id', 'category_attraction_id');
    }
}
