<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'admission_number',
        'ipd_number',
        'patient_id',
        'doctor_id',
        'room_id',
        'admission_date',
        'discharge_date',
        'reason',
        'admission_reason',
        'initial_diagnosis',
        'discharge_notes',
        'discharge_summary',
        'discharge_condition',
        'condition_on_discharge',
        'daily_rate',
        'initial_deposit',
        'status',
    ];

    public function setAdmissionNumberAttribute($value)
    {
        $this->attributes['admission_number'] = $value;
        $this->attributes['ipd_number'] = $value;
    }

    public function setIpdNumberAttribute($value)
    {
        $this->attributes['ipd_number'] = $value;
        $this->attributes['admission_number'] = $value;
    }

    public function setReasonAttribute($value)
    {
        $this->attributes['reason'] = $value;
        $this->attributes['admission_reason'] = $value;
    }

    public function setAdmissionReasonAttribute($value)
    {
        $this->attributes['admission_reason'] = $value;
        $this->attributes['reason'] = $value;
    }

    public function setDischargeNotesAttribute($value)
    {
        $this->attributes['discharge_notes'] = $value;
        $this->attributes['discharge_summary'] = $value;
    }

    public function setDischargeSummaryAttribute($value)
    {
        $this->attributes['discharge_summary'] = $value;
        $this->attributes['discharge_notes'] = $value;
    }

    public function getAdmissionNumberAttribute($value)
    {
        return $value ?: $this->ipd_number;
    }

    public function getReasonAttribute($value)
    {
        return $value ?: $this->admission_reason;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
