<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getCart(Request $request){
        $limit = $request->limit? $request->limit : 5;
        $total = 0;
        $userId = $request->userId? $request->userId : Auth::user()->id;
        $data = Cart::where('userId', $userId)->with('vproduct')->with('mproduct')->limit($limit)->get();
        foreach($data as $item){
            if($item['vproduct']['mainproduct']['discount']>0){
                $total +=  $item['quantity'] * ($item['vproduct']['sellingPrice'] - ($item['vproduct']['sellingPrice'] * $item['vproduct']['mainproduct']['discount'] /100)) ;
            }else{
                $total += $item['quantity'] * $item['vproduct']['sellingPrice'];
            }
        }
        return response()->json([
            'status' => true,
            'userId' => $userId,
            'data'=> $data,
            'total'=>$total
        ]);
    }
    public function addCart(Request $request){
        $data = $request->all();
        try{
            $user_id = Auth::user()->id;
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized!'
              ],401);
        }
            $userId = Auth::user()->id;
            $isNew=true;
            // $stock = Product::where('id',$data['productId'])->first();
            $isCarted = Cart::where('userId',$userId)->where('productId',$data['productId'])->with('vproduct')->with('mproduct')->first();
            if($isCarted){
                $newQuantity = $isCarted['quantity'] +  $data['quantity'];
                $stock =  $isCarted['vproduct']['stock'];
                if($newQuantity > $stock){
                    return response()->json([
                        'success' => false,
                        'message' => 'Product out of stock!'
                      ],401);
                }
                $isNew = false;
                $product = Cart::where('userId',$userId)->where('productId',$data['productId'])->update(['quantity'=>$newQuantity]);
            }else{
                $cart = [
                    'userId'=>$userId,
                    'productId'=>$data['productId'],
                    'mproductId'=>$data['mproductId'],
                    'categoryId'=>$data['categoryId'],
                    'subcategoryId'=>$data['subcategoryId'],
                    'menuId'=>$data['menuId'],
                    'product'=>json_encode($data),
                    'quantity'=>$data['quantity'],
                ];
                $product =  Cart::create($cart);
            }
            $allCart= Cart::where('userId',$userId)->with('vproduct')->with('mproduct')->get();
            return response()->json([
                'success'=> true,
                'isNew'=>$isNew,
                'allCarts' => $allCart,
            ],200);


    }
    public function updateCart(Request $request){
        $data = $request->all();
        try{
            $user_id = Auth::user()->id;
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized!'
              ],401);
        }
            $user_id = Auth::user()->id;
            $cart = Cart::where('id',$data['id'])->update(['quantity'=>$data['quantity']]);
            $product = Cart::where('id',$data['id'])->with('vproduct')->with('mproduct')->first();
            return response()->json([
                'success' => true,
                'product' => $product,
            ],200);
    }
    public function removeCart(Request $request)
    {
        $id = $request->id;
        $remove = Cart::where('id', $id)->delete();
        return response()->json(['msg' => 'Removed', 'status' => $remove], 200);

    }
}
