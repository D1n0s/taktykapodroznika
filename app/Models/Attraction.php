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
        'category_id',
        'time_start',
        'time_end',
        'duration',
        'mark_id',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];


    public function category()
    {
        return $this->belongsTo(CategorieAttraction::class, 'category_id', 'category_attraction_id');
    }

    public function setDurationAttribute($value){
        if ($value instanceof \DateInterval) {
            $hours = $value->h;
            $minutes = $value->i;

            // Utwórz ciąg w formacie 'H:i'
            $formattedDuration = sprintf('%02d:%02d', $hours, $minutes);

            $this->attributes['duration'] = $formattedDuration;
        } else {
            // Jeśli $value nie jest obiektem DateInterval, załóż, że jest już w formie ciągu znaków
            $this->attributes['duration'] = Carbon::parse($value)->format('H:i');
        }
    }
    public function getTime($columnName)
    {
        if (!in_array($columnName, ['duration', 'time_start', 'time_end'])) {
            // Obsługa błędu, jeśli przekazano nieprawidłową nazwę kolumny
            throw new \InvalidArgumentException('Invalid column name');
        }
        $columnValue = $this->attributes[$columnName];
        return $columnValue !== null ? Carbon::parse($columnValue)->format('H:i') : null;
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
