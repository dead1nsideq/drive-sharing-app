<?php

namespace App\Http\Controllers;

use App\Events\CancelTrip;
use App\Events\TripAccepted;
use App\Events\TripCreated;
use App\Events\TripEnded;
use App\Events\TripLocationUpdated;
use App\Events\TripStarted;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{

    public function store(Request $request) {
        $trip = $request->user()->trips()->where('status',['not_started','in_progress'])->first();
        if ($trip) {
            return response(['message' => 'You already have trip,cancel last before start new'],204);
        }

        // validate request
        $data = $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required',
        ]);

        $trip = $request->user()->trips()->create($data);

        TripCreated::dispatch($trip,$request->user());

        return $trip;
    }

    public function show(Request $request,Trip $trip) {
        if ($request->user()->can('access',$trip)) {
            return $trip;
        }
        return response(['message' => 'You cannot access this trip'],403);
    }

    public function current(Request $request) {
        if (is_null($request->user()->driver)) {
            $trip = $request->user()->trips()->whereIn('status', ['not_started', 'in_progress'])->first();
        } else {
            $trip = $request->user()->trips()->whereIn('status', ['not_started', 'in_progress'])
                ->orWhere('driver_id', $request->user()->driver->id)->first();
        }
        if ($trip) {
            return $trip->load('driver.user');
        } else {
            return response(['message' => 'no trip'],204);
        }

    }

    // accept trip can be done by driver only
    public function accept(Request $request,Trip $trip) {
        // driver accept the trip

//        if ($request->user()->cannot('handle',$trip)) {
//            return response(['message' => 'You cannot accept this trip'],403);
//        }

        $data = $request->validate([
            'driver_location' => 'required'
        ]);

        $data['driver_id'] = $request->user()->driver->id;
        $trip->update([
            'status' => 'in_progress'
        ]);
        $trip->update($data);

        $trip->load('driver.user');

        TripAccepted::dispatch($trip,$trip->user);

        return $trip;
    }
    // to start a trip both driver and user should be in the same location (origin === driver_location)
    public function start(Request $request,Trip $trip) {
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
    // to end a trip both driver and user should be in the same location (destination === driver_location)
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

    // cancel trip can be done by both user and driver but only if the trip is not started
    public function cancel(Request $request,Trip $trip) {
        // they have arrived
        if ($request->user()->cannot('access',$trip)) {
            return response(['message' => 'You cannot cancel this trip'],403);
        }

        CancelTrip::dispatch($trip->id);

        $trip->delete();

        return response(['message' => 'trip deleted']);
    }

    // обновление местоположения водителя или пассажира
    public function location(Request $request,Trip $trip) {
        // todo policy return responce
        if ($request->user()->cannot('access',$trip)) {
            return response(['message' => 'You cannot have access to this trip'],403);
        }

        $data = $request->validate([
            'driver_location' => 'required'
        ]);

        $trip->update($data);

        $trip->load('driver.user');

        TripLocationUpdated::dispatch($trip,$request->user());
        return $trip;
    }

}
