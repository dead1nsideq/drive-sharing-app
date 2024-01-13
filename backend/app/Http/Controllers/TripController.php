<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class TripController extends Controller
{
    public function store(Request $request) {
        // validate request
        $data = $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required',
        ]);

        $data['origin'] = json_decode($data['origin'],true);
        $data['destination'] = json_decode($data['destination'],true);

        return $request->user()->trips()->create($data);
    }

    // TODO maybe it should be a policy
    public function show(Request $request,Trip $trip) {
        if (($trip->user->id === $request->user()->id) || ($trip->driver?->id === $request->user()->driver?->id)) {
            return $trip;
        }
        return response(['message' => 'You cannot access this trip'],403);
    }

    public function accept(Request $request,Trip $trip) {
        // driver accept the trip
        $request->validate([
            'driver_location' => 'required'
        ]);

        $trip->update([
            'driver_id' => $request->user()->id,
            'driver_location' => $request->only('driver_location')
        ]);

        $trip->load('driver.user');

        return $trip;
    }
    public function start(Request $request,Trip $trip) {
        // driver arrived, passenger and driver starts the trip
        $trip->update([
            'status' => 'in_progress'
        ]);

        $trip->load('driver.user');

        return $trip;
    }
    public function end(Request $request,Trip $trip) {
        // they have arrived
        $trip->update([
            'status' => 'completed'
        ]);

        $trip->load('driver.user');

        return $trip;
    }
    public function location(Request $request,Trip $trip) {
        $request->validate([
            'driver_location' => 'required'
        ]);

        $trip->update([
            'driver_location' => $request->only('driver_location')
        ]);

        $trip->load('driver.user');

        return $trip;
    }
}
