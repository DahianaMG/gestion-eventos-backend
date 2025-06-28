<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'user_id',
        'display_name',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function votes()
    {
        return $this->morphMany(Vote::class, 'target');
    }
}
