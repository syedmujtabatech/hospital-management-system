<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id',
        'item_type',
        'item_name',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function setItemNameAttribute($value)
    {
        $this->attributes['item_name'] = $value;
        $this->attributes['description'] = $value;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value;
        $this->attributes['item_name'] = $value;
    }

    public function getDescriptionAttribute($value)
    {
        return $value ?: $this->item_name;
    }

    public function getItemNameAttribute($value)
    {
        return $value ?: $this->description;
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
