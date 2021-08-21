<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class CarController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $car = Car::get();

        if (!empty($car)) {
            $response = [
                'code'  => 201,
                'status'    => true,
                'message'    => "Data found",
                'car'    => $car,
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'code'  => 400,
                'status'    => false,
                'message'    => "Data not found",
                'car'    => "",
            ];
            return response()->json($response, 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $car = Car::where("id", $id)->first();

        if (!empty($car)) {
            $response = [
                'code'  => 201,
                'status'    => true,
                'message'    => "Data found",
                'car'    => $car,
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'code'  => 400,
                'status'    => false,
                'message'    => "Data not found",
                'car'    => "",
            ];
            return response()->json($response, 400);
        }
    }

    /**
     * Store new car
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|max:255',
            'modal' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'price' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $response = [
                'code'  => 400,
                'status'    => false,
                'message'    => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $car = Car::create([
            'brand' => $request->get('brand'),
            'modal' => $request->get('modal'),
            'year' => $request->get('year'),
            'price' => $request->get('price'),
            'color' => $request->get('color'),
            'fuel' => $request->get('fuel'),
            'kilometer' => $request->get('kilometer'),
            'mileage' => $request->get('mileage'),
            'no_of_owner' => $request->get('no_of_owner'),
            'location' => $request->get('location'),
            'description' => $request->get('description'),
            'user_id' => $user_id,
        ]);

        $response = [
            'code'  => 201,
            'status'    => true,
            'message'    => "Created Successfully",
            'car'    => $car,
        ];
        return response()->json($response, 201);
    }


    /**
     * Update old car
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|max:255',
            'modal' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'price' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $response = [
                'code'  => 400,
                'status'    => false,
                'message'    => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $car = Car::where("id", $request->get('id'))->first();

        if (!empty($car)) {
            $car->brand = $request->get('brand');
            $car->modal = $request->get('modal');
            $car->year = $request->get('year');
            $car->price = $request->get('price');
            $car->color = $request->get('color');
            $car->fuel = $request->get('fuel');
            $car->kilometer = $request->get('kilometer');
            $car->mileage = $request->get('mileage');
            $car->no_of_owner = $request->get('no_of_owner');
            $car->location = $request->get('location');
            $car->description = $request->get('description');
            $car->user_id = $user_id;
            $car->save();

            $response = [
                'code'  => 201,
                'status'    => true,
                'message'    => "Created Successfully",
                'car'    => $car,
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'code'  => 400,
                'status'    => false,
                'message'    => "Car not found",
                'car'    => $car,
            ];
            return response()->json($response, 400);
        }
    }


    /**
     * User car list
     *
     * @return \Illuminate\Http\Response
     */
    public function userCars(Request $request)
    {
        $user_id = JWTAuth::parseToken()->authenticate()->id;

        // $user_id = 1;
        $cars = Car::where("user_id", $user_id)->get();

        if (!empty($cars)) {


            $response = [
                'code'  => 201,
                'status'    => true,
                'message'    => "Data found",
                'cars'    => $cars,
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'code'  => 400,
                'status'    => false,
                'message'    => "Data not found",
                'cars' => ''
            ];
            return response()->json($response, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Car::where('id', '=', $id)->first();
        if (!empty($car)) {
            $car->delete();
            $response = [
                'code'  => 201,
                'status'    => true,
                'message'    => "Deleted Successfully",

            ];
            return response()->json($response, 201);
        } else {

            $response = [
                'code'  => 400,
                'status'    => false,
                'message'    => "Data not found",
                'cars' => ''
            ];
            return response()->json($response, 400);
        }
    }
}
