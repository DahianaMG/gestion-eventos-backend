<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationUser extends Model
{
    use HasFactory;

    protected $table = 'notification_user';

    protected $fillable = [
        'user_id',
        'notification_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
