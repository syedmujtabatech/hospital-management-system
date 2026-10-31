<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialization',
        'qualification',
        'license_number',
        'consultation_fee',
        'schedule_days',
        'schedule_hours',
        'room_number',
        'room_no',
        'bio',
        'avatar',
        'status',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $parts = explode(' ', $value, 2);
        if (empty($this->attributes['first_name'])) {
            $this->attributes['first_name'] = $parts[0] ?? $value;
        }
        if (empty($this->attributes['last_name'])) {
            $this->attributes['last_name'] = $parts[1] ?? '';
        }
    }

    public function getFirstNameAttribute($value)
    {
        if ($value) return $value;
        $parts = explode(' ', $this->name ?? '', 2);
        return $parts[0] ?? '';
    }

    public function getLastNameAttribute($value)
    {
        if ($value) return $value;
        $parts = explode(' ', $this->name ?? '', 2);
        return $parts[1] ?? '';
    }

    public function getRoomNoAttribute($value)
    {
        return $value ?: $this->room_number;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function encounters()
    {
        return $this->hasMany(Encounter::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
