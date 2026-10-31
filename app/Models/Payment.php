<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'bill_id',
        'patient_id',
        'receipt_number',
        'amount',
        'payment_method',
        'transaction_id',
        'transaction_reference',
        'payment_date',
        'received_by',
        'notes',
    ];

    public function setTransactionIdAttribute($value)
    {
        $this->attributes['transaction_id'] = $value;
        $this->attributes['transaction_reference'] = $value;
    }

    public function setTransactionReferenceAttribute($value)
    {
        $this->attributes['transaction_reference'] = $value;
        $this->attributes['transaction_id'] = $value;
    }

    public function getTransactionReferenceAttribute($value)
    {
        return $value ?: $this->transaction_id;
    }

    public function getTransactionIdAttribute($value)
    {
        return $value ?: $this->transaction_reference;
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
