<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'prescription_number',
        'encounter_id',
        'patient_id',
        'doctor_id',
        'prescription_date',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
