<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function store(Request $request) {
        // validate request
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required',
        ]);

        return $request->user()->trips()->create($request->only(
            ['origin','destination','destination_name']
        ));
    }

    // TODO maybe it should be a policy
    public function show(Request $request,Trip $trip) {
        if (($trip->user->id === $request->user()->id) || ($trip->driver?->id === $request->user()->driver?->id)) {
            return $trip;
        }

        return response(['message' => 'Cannot find this trip'],404);
    }
}
