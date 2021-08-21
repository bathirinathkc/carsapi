<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\Utillity;
use JWTAuth;
use Auth;

class CarController extends Controller
{
    use Utillity;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $offset_input = $request->input('offset');
            $limit_input = $request->input('limit');
            $offset = isset($offset_input) ? $offset_input : 0;
            $limit = isset($limit_input) ? $limit_input : 10;
            
            $car = Car::with('users')->offset($offset)->limit($limit)->get();

            if(count($car) == 0){
                $response = $this->getReponse(false, 400, 'Data Not Found');
            }else{
                $car['offset'] = $offset;
                $car['limit'] = $limit;
                $car['count'] = Car::count(); 
                $response = $this->getReponse(true, 200, 'Success', $car);
            }
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }

        return $response;
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
     * Funciton to store new car
     * * @param {*} brand 
     * * @param {*} modal 
     * * @param {*} year 
     * * @param {*} price 
     * @return
    */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'brand' => 'required|string|max:255',
                'modal' => 'required|string|max:255',
                'year' => 'required|string|max:255',
                'price' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $response = $this->getReponse(false, 400, 'Validation Error',$validator->errors());
            }
            $user = Auth::user();
            $user_id = $user['id'];

            $car = Car::create([
                'brand' => $request->get('brand'),
                'modal' => $request->get('modal'),
                'year' => $request->get('year'),
                'price' => $request->get('price'),
                'color' => $request->get('color'),
                'description' => $request->get('description'),
                'user_id' => $user_id,
                'user' => $user
            ]);

            $response = $this->getReponse(true, 200, 'Car Created Successfully',$car);
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }

        return $response;
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
        try {
            $offset_input = $request->input('offset');
            $limit_input = $request->input('limit');
            $offset = isset($offset_input) ? $offset_input : 0;
            $limit = isset($limit_input) ? $limit_input : 10;

            $user_id = JWTAuth::parseToken()->authenticate()->id;
            $cars = Car::where("user_id", $user_id)->offset($offset)->limit($limit)->get();

            if(count($cars) == 0){
                $response = $this->getReponse(false, 400, 'Data Not Found');
            }else{
                $cars['offset'] = $offset;
                $cars['limit'] = $limit;
                $cars['count'] = Car::where("user_id", $user_id)->count(); 
                $response = $this->getReponse(true, 200, 'Success', $cars);
            }
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }

        return $response;
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
