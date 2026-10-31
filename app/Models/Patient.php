<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'mrn',
        'first_name',
        'last_name',
        'date_of_birth',
        'age',
        'gender',
        'blood_group',
        'phone',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'allergies',
        'chronic_conditions',
        'notes',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
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

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
