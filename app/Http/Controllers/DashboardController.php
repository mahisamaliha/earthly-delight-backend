<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrderDetails;

use App\Models\UserCity;
use App\Models\Zone;
use App\Models\UserArea;

use App\Models\Customer;
use App\Models\Setting;
use Auth;


class DashboardController extends Controller
{
    public function getCompanySetting(Request $request){
        return Setting::first();
    }
    public function getProfileInfo($id){
        $user = User::where('id', $id)->get();
        return response()->json([
            'success' => true,
            'user' => $user,
        ], 200);
    }

    public function getOrdersInfo(){
        $user_id = Auth::user()->id;
        $data = Orders::where('userId', $user_id)->with('orderDetails.product')->orderBy('id','desc')->get();

        $formattedData = [];
        foreach($data as $value){
            $product = $value;
            unset($product['description']);
            unset($product['brief_description']);
            unset($product['description']);
            unset($product['averageBuyingPrice']);
            unset($product['unit']);
            unset($product['isFeatured']);
            unset($product['adminDiscount']);
            unset($product['appDiscount']);
            unset($product['openingQuantity']);
            unset($product['openingUnitPrice']);

            // $product['productName'] = $value->order_details->product->productName;
            // $product['brandName'] = $value->brand->name;
            // $product['catName'] = $value->category->catName;
            // unset($product['group']);
            // unset($product['brand']);
            // unset($product['category']);

            array_push($formattedData, $product);
        }
        return response()->json([
            'success'=> true,
            'data'=>$formattedData,
        ],200);
    }

    public function getOrderDetails($id){
        // $query =  Orders::with('orderDetails.product');
        $data = OrderDetails::where('orderId', $id)->with('product')->first();

        return response()->json([
            'success'=> true,
            'data'=>$data,
        ],200);
    }

    public function getCities(Request $request){
        $limit = $request->limit;
        if($limit){
            $city = UserCity::limit($limit)->get();
        }else{
            $city = UserCity::get();
        }
        return response()->json([
            'success'=> true,
            'data'=>$city,
        ],200);
    }

    public function getZone(Request $request){
        $city = $request->city;
        if($city){
            $zone = Zone::where('city_id', $request->city)->get();
        }else{
            $zone = Zone::get();
        }

        return response()->json([
            'success'=> true,
            'data'=>$zone,
        ],200);
    }

    public function getArea(Request $request,$id){
        // return response()->json([
        //     'success'=> true,
        //     'data'=>$id,
        // ],200);
        $area = UserArea::where('zone_id', $id)->get();

        return response()->json([
            'success'=> true,
            'data'=>$area,
        ],200);
    }

    public function getCustomerInfo(){
        $user_id = Auth::user()->id;

        $customer = Customer::where('userId', $user_id)->first();
        \Log::info($customer);

        return response()->json([
            'success'=> true,
            'data'=>$customer,
        ],200);
    }

    public function saveProfileInfo(Request $request){
        $user_id = Auth::user()->id;
        return $request;
        $user = User::where('id', $user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'username' => $request->username,
        ]);
        \Log::info($request);
        $customer = Customer::where('userId', $user_id)->update([
            'customerName'=> $request->customer->customerName,
            'contact' => $request->customer->contact,
            'address' => $request->customer->address,
            'email' => $request->customer->email,
            'zone' => $request->customer->zone,
            'facebook' => $request->customer->facebook,
            'instagram' => $request->customer->instagram,
            'cityId' => $request->customer->cityId,
            'zoneId' => $request->customer->zoneId,
            'areaId' => $request->customer->areaId,
            'postCode' => $request->customer->postCode,
        ]);
        if($request->password){
            $user = User::where('id', $user_id)->update([
                'password' => $request->password,
            ]);
        }
        return response()->json([
            'success'=> true,
            'data'=>$customer,
        ],200);
    }
}
