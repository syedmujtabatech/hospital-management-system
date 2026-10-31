<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'dosage_form',
        'batch_number',
        'cost_price',
        'purchase_price',
        'selling_price',
        'sale_price',
        'stock_quantity',
        'alert_quantity',
        'expiry_date',
        'manufacturer',
        'status',
    ];

    public function setCostPriceAttribute($value)
    {
        $this->attributes['cost_price'] = $value;
        $this->attributes['purchase_price'] = $value;
    }

    public function setPurchasePriceAttribute($value)
    {
        $this->attributes['purchase_price'] = $value;
        $this->attributes['cost_price'] = $value;
    }

    public function setSellingPriceAttribute($value)
    {
        $this->attributes['selling_price'] = $value;
        $this->attributes['sale_price'] = $value;
    }

    public function setSalePriceAttribute($value)
    {
        $this->attributes['sale_price'] = $value;
        $this->attributes['selling_price'] = $value;
    }

    public function getCostPriceAttribute($value)
    {
        return $value ?: $this->purchase_price;
    }

    public function getSellingPriceAttribute($value)
    {
        return $value ?: $this->sale_price;
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
