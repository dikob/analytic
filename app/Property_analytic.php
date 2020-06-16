<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property_analytic extends Model
{
    protected $casts = [
        'value' => 'integer',
    ];
    //
    public function property() {
        return $this->belongsTo(property::class, 'property_id');
    }
}
