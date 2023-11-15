<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    protected $primaryKey = 'attraction_id';
    public $timestamps = false; // Zakładam, że tabela nie ma kolumny 'created_at' i 'updated_at'

    protected $fillable = [
        'post_id',
        'title',
        'desc',
        'cost',
        'duration',
        'mark_id',
    ];

    protected $casts = [
        'cost' => 'decimal:2', // Rzutowanie na liczbę zmiennoprzecinkową z dwoma miejscami po przecinku
        'duration' => 'datetime', // Rzutowanie na typ daty i czasu
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function mark()
    {
        return $this->belongsTo(Mark::class, 'mark_id', 'mark_id');
    }
}
