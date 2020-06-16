<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    //
    public function property_analytic() {
        return $this->hasMany(Property_analytic::class);
    }
}
