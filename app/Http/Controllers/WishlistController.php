<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishList;

class WishListController extends Controller
{
    public function getWishlist(Request $request){
        $user=  $request->user;
        $product=  $request->product;
        $userId=  $request->userId;
        $data =  WishList::with('user', 'product');
        if($userId){
            $data->orWhere('userId', $userId);
        }
        if($user){
            $data->whereHas('user', function ($query) use ($user){
                $query->where('name', 'like', '%'.$user.'%');
                $query->orWhere('contact', 'like', '%'.$user.'%');
                $query->orWhere('id', $user);

            });
        }
        if($product){
            $data->whereHas('product', function ($query) use ($product){
                $query->where('productName', 'like', '%'.$product.'%');
                $query->orWhere('model', 'like',  '%'. $product. '%' );
            });
        }

        return $data->orderBy('id','desc')->paginate(10);

    }
}
