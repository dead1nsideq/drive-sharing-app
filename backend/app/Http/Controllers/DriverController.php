<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class DriverController extends Controller {
    public function show(Request $request) {

        // return back user and associated driver model
        $user = $request->user();
        $user->load('driver');

        // $user->driver (null or not)

        return $user;
    }

    public function trips() {
        return Trip::where('driver_id',null)->get();
    }

    public function update(Request $request) {
        $request->validate([
            'year' => 'required|integer|between:1960,2024',
            'make' => 'required|string',
            'model' => 'required|string',
            'color' => 'required|alpha',
            'licence_plate' => 'required|string',
        ]);

        $user = $request->user();

        $user->driver()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['year', 'make', 'model', 'color', 'licence_plate'])
        );

        $user->load('driver');

        return $user;
    }
}
