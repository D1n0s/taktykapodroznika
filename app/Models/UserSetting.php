<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'notification_preference',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
