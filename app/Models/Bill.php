<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'bill_number',
        'invoice_number',
        'patient_id',
        'encounter_id',
        'admission_id',
        'bill_date',
        'due_date',
        'subtotal',
        'discount',
        'tax',
        'tax_percentage',
        'total_amount',
        'net_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'notes',
    ];

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
        $this->attributes['payment_status'] = ucfirst(str_replace('_', ' ', $value));
    }

    public function setPaymentStatusAttribute($value)
    {
        $this->attributes['payment_status'] = $value;
        $this->attributes['status'] = strtolower(str_replace(' ', '_', $value));
    }

    public function setBillNumberAttribute($value)
    {
        $this->attributes['bill_number'] = $value;
        $this->attributes['invoice_number'] = $value;
    }

    public function setInvoiceNumberAttribute($value)
    {
        $this->attributes['invoice_number'] = $value;
        $this->attributes['bill_number'] = $value;
    }

    public function getBillNumberAttribute($value)
    {
        return $value ?: $this->invoice_number;
    }

    public function getInvoiceNumberAttribute($value)
    {
        return $value ?: $this->bill_number;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
