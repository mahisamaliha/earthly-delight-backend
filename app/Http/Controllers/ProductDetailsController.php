<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainProduct;
use App\Models\WebsiteBanner;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\ProductVariation;

use App\Models\Review;
use Auth;


class ProductDetailsController extends Controller
{
    public function productDetails(Request $request, $id){
        $data = $request->all();
        $userId = $data['userId'];
        if($data['userId']){
            $data = MainProduct::where('slug',$id)->with('category','brand','productImages','review.users','productVariation.values', 'averageRating')->with('wishlist',function($query) use ($data){
                $query->where('userId',$data['userId']);
            })->first();

        }else{
            $data = MainProduct::where('slug',$id)->with('category','brand','productImages','review.users','productVariation.values', 'averageRating')->first();
        }
        // $product = ProductVariation::where('mproductId',$data['id'])->with('values')->get();
        $relatedProduct=MainProduct::where('categoryId',$data['categoryId'])->where('id','!=',$data['id'])->limit(10)->get();
        // $trendingOffers = MainProduct::where('isNew', 1)->where('isHotDeal', 1)->where('is_archived',0)->limit(4)->get();

        $formattedData = [];

        unset($data['averageBuyingPrice']);
        unset($data['unit']);
        unset($data['adminDiscount']);
        unset($data['appDiscount']);
        unset($data['openingQuantity']);
        unset($data['openingUnitPrice']);

        $data['catName'] = $data->category->catName;
        $data['brandName'] = $data->brand->name;
        $data['groupeName'] = $data->group->groupName;

        unset($data['category']);
        unset($data['brand']);
        unset($data['group']);
        array_push($formattedData, $data);
        return response()->json([
            'success'=> true,
            'data'=>$formattedData,
            'relatedProduct'=>$relatedProduct,
            // 'trending'=>$trendingOffers

        ],200);
        // return response()->json([
        //     'success'=> true,
        //     'data'=>$data,
        //     'trending'=>$trendingOffers
        // ],200);
    }
    public function getVariableProduct(Request $request){
        $data = $request->all();
        $data['variation'] = json_encode($data['variation']);
        $product = Product::where('mproductId',$data['id'])->where('variation',$data['variation'])->with('productImages')->first();
        return $product;
    }
    public function reviews($id){
        return Review::with('users')->where('productId',$id)->get();
    }
    public function addReview(Request $request){
        // $request['userId'] = Auth::user()->id;
        return Review::create($request->all());
    }
}
