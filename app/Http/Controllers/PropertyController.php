<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Property;
use App\Property_analytic;
use App\Http\Resources\Property as PropertyResource;
use App\Http\Resources\Property_analytic as Property_analyticResource;

class PropertyController extends Controller
{
    /**
     * Add a new property
     *
     * @return \Illuminate\Http\Response
     */
    public function addProperty(Request $request)
    {
        //
        $property = new Property;

        $property->guid = Str::uuid()->toString();
        $property->suburb = $request->suburb;
        $property->state = $request->state;
        $property->country = $request->country;

        if ($property->save()) {
            return new PropertyResource($property);
        }
    }

    /**
     * Add/Update an analytic to a property
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function analytic(Request $request)
    {
        //
        $analytic = $request->isMethod('patch') ? Property_analytic::findOrFail($request->id) : new Property_analytic;

        $analytic->property_id = $request->property_id;
        $analytic->analytic_type_id = $request->analytic_type_id;
        $analytic->value = $request->value;

        if ($analytic->save()) {
            return new Property_analyticResource($analytic);
        }
    }

    /**
     * Get all analytics for an inputted property
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAnalytics(Request $request)
    {

        if ($request->id) {
            $properties = Property::findOrFail($request->id)->property_analytic;

            // Return collection of analytics an inputted property
            return new PropertyResource($properties);
        } else {

            // Get by location type
            if ($needle = $request->suburb) {
                $analytics = \App\Property_analytic::whereHas('property', function ($query) use ($needle) {
                    $query->where("suburb", '=', $needle);
                })->get();
            } elseif ($needle = $request->state) {
                $analytics = \App\Property_analytic::whereHas('property', function ($query) use ($needle) {
                    $query->where("state", '=', $needle);
                })->get();
            } elseif ($needle = $request->country) {
                $analytics = \App\Property_analytic::whereHas('property', function ($query) use ($needle) {
                    $query->where("country", '=', $needle);
                })->get();
            }

            return array('min' => $analytics->min('value'), 'max' => $analytics->max('value'), 'median' => $analytics->median('value'), 'percentage with value' => $a = ((($analytics->pluck('value')->count()) / $analytics->count()) * 100) . '%', 'percentage without value' => ((int) $a - 100) . '%');
        }
    }
}
