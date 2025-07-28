<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date_time',
        'location',
        'has_fair',
        'capacity',
    ];

    protected $casts = [
        'has_fair' => 'boolean',
        'date_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'registrations', 'event_id', 'user_id');
    }
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
