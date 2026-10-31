<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOrder extends Model
{
    protected $fillable = [
        'order_number',
        'patient_id',
        'doctor_id',
        'lab_test_id',
        'order_date',
        'priority',
        'clinical_notes',
        'sample_collected_at',
        'result_date',
        'result_value',
        'result_values',
        'result_notes',
        'technician_remarks',
        'status',
    ];

    public function setResultValueAttribute($value)
    {
        $this->attributes['result_value'] = $value;
        $this->attributes['result_values'] = $value;
    }

    public function setResultValuesAttribute($value)
    {
        $this->attributes['result_values'] = $value;
        $this->attributes['result_value'] = $value;
    }

    public function setResultNotesAttribute($value)
    {
        $this->attributes['result_notes'] = $value;
        $this->attributes['technician_remarks'] = $value;
    }

    public function setTechnicianRemarksAttribute($value)
    {
        $this->attributes['technician_remarks'] = $value;
        $this->attributes['result_notes'] = $value;
    }

    public function getResultValueAttribute($value)
    {
        return $value ?: $this->result_values;
    }

    public function getResultNotesAttribute($value)
    {
        return $value ?: $this->technician_remarks;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
