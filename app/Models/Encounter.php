<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    protected $fillable = [
        'encounter_number',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'encounter_type',
        'encounter_date',
        'visit_date',
        'chief_complaint',
        'symptoms',
        'blood_pressure',
        'bp',
        'pulse_rate',
        'pulse',
        'temperature',
        'respiratory_rate',
        'spo2',
        'weight',
        'diagnosis',
        'treatment_notes',
        'doctor_advice',
        'status',
    ];

    public function setEncounterDateAttribute($value)
    {
        $this->attributes['encounter_date'] = $value;
        $this->attributes['visit_date'] = $value;
    }

    public function setVisitDateAttribute($value)
    {
        $this->attributes['visit_date'] = $value;
        $this->attributes['encounter_date'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    public function setBloodPressureAttribute($value)
    {
        $this->attributes['blood_pressure'] = $value;
        $this->attributes['bp'] = $value;
    }

    public function setBpAttribute($value)
    {
        $this->attributes['bp'] = $value;
        $this->attributes['blood_pressure'] = $value;
    }

    public function setPulseRateAttribute($value)
    {
        $this->attributes['pulse_rate'] = $value;
        $this->attributes['pulse'] = $value;
    }

    public function setPulseAttribute($value)
    {
        $this->attributes['pulse'] = $value;
        $this->attributes['pulse_rate'] = $value;
    }

    public function setTreatmentNotesAttribute($value)
    {
        $this->attributes['treatment_notes'] = $value;
        $this->attributes['doctor_advice'] = $value;
    }

    public function setDoctorAdviceAttribute($value)
    {
        $this->attributes['doctor_advice'] = $value;
        $this->attributes['treatment_notes'] = $value;
    }

    public function getEncounterDateAttribute($value)
    {
        return $value ?: ($this->visit_date ? date('Y-m-d', strtotime($this->visit_date)) : null);
    }

    public function getBloodPressureAttribute($value)
    {
        return $value ?: $this->bp;
    }

    public function getPulseRateAttribute($value)
    {
        return $value ?: $this->pulse;
    }

    public function getTreatmentNotesAttribute($value)
    {
        return $value ?: $this->doctor_advice;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
