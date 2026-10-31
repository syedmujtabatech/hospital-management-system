<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'test_name',
        'test_code',
        'category',
        'standard_cost',
        'price',
        'normal_range',
        'reference_range',
        'sample_type',
        'unit',
        'description',
        'status',
    ];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
        $this->attributes['standard_cost'] = $value;
    }

    public function setStandardCostAttribute($value)
    {
        $this->attributes['standard_cost'] = $value;
        $this->attributes['price'] = $value;
    }

    public function setReferenceRangeAttribute($value)
    {
        $this->attributes['reference_range'] = $value;
        $this->attributes['normal_range'] = $value;
    }

    public function setNormalRangeAttribute($value)
    {
        $this->attributes['normal_range'] = $value;
        $this->attributes['reference_range'] = $value;
    }

    public function getPriceAttribute($value)
    {
        return $value ?: $this->standard_cost;
    }

    public function getReferenceRangeAttribute($value)
    {
        return $value ?: $this->normal_range;
    }

    public function getSampleTypeAttribute($value)
    {
        return $value ?: 'Whole Blood / Serum';
    }

    public function orders()
    {
        return $this->hasMany(LabOrder::class);
    }
}
