<?php

namespace App\Models;

use Carbon\Carbon;
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
        'time_start',
        'time_end',
        'duration',
        'mark_id',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = Carbon::parse($value)->format('H:i');
    }
    public function getTime($columnName)
    {
        if (!in_array($columnName, ['duration', 'time_start', 'time_end'])) {
            // Obsługa błędu, jeśli przekazano nieprawidłową nazwę kolumny
            throw new \InvalidArgumentException('Invalid column name');
        }

        return Carbon::parse($this->attributes[$columnName])->format('H:i');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function mark()
    {
        return $this->belongsTo(Mark::class, 'mark_id', 'mark_id');
    }
}