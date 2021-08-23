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
     * Function to get all the cars details
     * * @param {*} offset 
     * * @param {*} limit 
     * @returns 
     */
    public function getallcars(Request $request)
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
     * Funciton to get user created cars details
     * * @param {*} offset 
     * * @param {*} limit 
     * @returns
     */
    public function getUserCreatedCars(Request $request)
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
     * Funciton to fetch car details 
     * * @param  int  $id
     **  @return
     */
    public function getCarDetail($id)
    {
        try {
            $car = Car::where("id", $id)->first();
            if(empty($car)){
                $response = $this->getReponse(false, 400, 'Data Not Found');
            }else{
                $response = $this->getReponse(true, 200, 'Success', $car);
            }
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }
    
        return $response;
    }

    /**
     * Funciton to create new car
     * * @param {*} brand 
     * * @param {*} modal 
     * * @param {*} year 
     * * @param {*} price 
     * * @param {*} color 
     * * @param {*} description 
     * @return
    */
    public function createCar(Request $request)
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
                'user_id' => $user_id
            ]);

            $response = $this->getReponse(true, 200, 'Car Created Successfully',$car);
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }

        return $response;
    }


    /**
     * Funciton to update the car
     * * @param  int  $id
     * * @param {*} brand 
     * * @param {*} modal 
     * * @param {*} year 
     * * @param {*} price 
     * * @param {*} color 
     * * @param {*} description 
     * @return
     */
    public function updateCar(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'brand' => 'required|string|max:255',
                'modal' => 'required|string|max:255',
                'year' => 'required|string|max:255',
                'price' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $response = $this->getReponse(false, 400, 'Validation Error',$validator->errors());
            }

            $car_id = $request->get('id');

            $car = Car::find($car_id);
            if($car){
                $car->brand = $request->get('brand');
                $car->modal = $request->get('modal');
                $car->year = $request->get('year');
                $car->price = $request->get('price');
                $car->color = $request->get('color');
                $car->description = $request->get('description');
                $car->save();
                $response = $this->getReponse(true, 200, 'Car Updatead Successfully',$car);
            }else{
                $response = $this->getReponse(false, 400, 'Data Not Found');
            }

        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }

        return $response;
    }

    /**
     * Function to remove the car
     * @param  int  $id
     * @return
     */
    public function deleteCar($id)
    {
        try {
            $car = Car::where('id', '=', $id)->first();
            if (!empty($car)) {
                $car->delete();
                $response = $this->getReponse(true, 200, 'Car Deleted Successfully');
            }else{
                $response = $this->getReponse(false, 400, 'Data Not Found');
            }
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());
        }

        return $response;
    }
}
