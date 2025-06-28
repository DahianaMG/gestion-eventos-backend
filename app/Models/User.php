<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    public function activityParticipants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class);
    }
}
