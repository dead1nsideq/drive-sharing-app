<?php

namespace App\Http\Controllers;

use App\Events\TripAccepted;
use App\Events\TripEnded;
use App\Events\TripLocationUpdated;
use App\Events\TripStarted;
use App\Models\Trip;
use Illuminate\Http\Request;

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

    public function show(Request $request,Trip $trip) {
        if ($request->user()->can('access',$trip)) {
            return $trip;
        }
        return response(['message' => 'You cannot access this trip'],403);
    }

    public function accept(Request $request,Trip $trip) {
        // driver accept the trip

        if ($request->user()->cannot('handle',$trip)) {
            return response(['message' => 'You cannot accept this trip'],403);
        }

        $data = $request->validate([
            'driver_location' => 'required'
        ]);

        $data['driver_location'] = json_decode($data['driver_location'],true);
        $data['driver_id'] = $request->user()->driver->id;
        $trip->update($data);

        $trip->load('driver.user');

        TripAccepted::dispatch($trip,$request->user());

        return $trip;
    }
    public function start(Request $request,Trip $trip) {
        // driver arrived, passenger and driver starts the trip

        if ($request->user()->cannot('handle',$trip)) {
            return response(['message' => 'You cannot start this trip'],403);
        }

        $trip->update([
            'status' => 'in_progress'
        ]);

        $trip->load('driver.user');

        TripStarted::dispatch($trip,$request->user());
        return $trip;
    }
    public function end(Request $request,Trip $trip) {
        // they have arrived
        if ($request->user()->cannot('handle',$trip)) {
            return response(['message' => 'You cannot end this trip'],403);
        }

        $trip->update([
            'status' => 'completed'
        ]);

        $trip->load('driver.user');

        TripEnded::dispatch($trip,$request->user());
        return $trip;
    }
    public function location(Request $request,Trip $trip) {
        if ($request->user()->cannot('access',$trip)) {
            return response(['message' => 'You cannot start this trip'],403);
        }

        $data = $request->validate([
            'driver_location' => 'required'
        ]);

        $data['driver_location'] = json_decode($data['driver_location'],true);

        $trip->update($data);

        $trip->load('driver.user');

        TripLocationUpdated::dispatch($trip,$request->user());
        return $trip;
    }
}
