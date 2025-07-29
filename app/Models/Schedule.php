<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'activity_name',
        'start_time',
        'end_time',
        'location_description',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }
    public function votes()
    {
        return $this->morphMany(Vote::class, 'target');
    }
}
