<?php

namespace App\Http\Controllers;
use App\Models\WebsiteBanner;
use App\Models\WebsiteHotDeal;
use App\Models\MainProduct;
use App\Models\Category;
use App\Models\WebsiteArticle;
use App\Models\WebsiteMailingList;
use App\Models\WebsiteMailingListBg;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getBanner()
    {
        $topPromotionalBanner = WebsiteBanner::where('type', 'topPromotionalBanner')->get();
        $slider = WebsiteBanner::where('type', 'slider')->get();
        $middleBanner = WebsiteBanner::where('type', 'middleBanner')->get();
        $trendingOffers = WebsiteBanner::where('type', 'trendingOffers')->get();
        $data = WebsiteBanner::get();


        return response()->json([
            'success'=> true,
            // 'topPromotionalBanner'=>$topPromotionalBanner,
            // 'slider'=>$slider,
            // 'middleBanner'=>$middleBanner,
            // 'trendingOffers'=>$trendingOffers
            'data'=>$data

        ],200);
    }
    public function getHotDeals()
    {

        $data = WebsiteHotDeal::with('products')->where('isHotSale', 1)->first();

        if($data){
            $products = $data->products;
            $formattedProducts = [];
            foreach($products as $p){
                $product = $p->product;
                unset($product['description']);
                unset($product['brief_description']);
                unset($product['averageBuyingPrice']);
                unset($product['unit']);
                unset($product['openingQuantity']);
                unset($product['openingUnitPrice']);
                array_push($formattedProducts, $product);

            }

            // return $formattedProducts;
            $formattedData = [
                "id"=> 1,
                "title"=> "Winter Sale",
                "percentageTitle"=> "Upto 40% OFF",
                "startDate"=> "2022-12-15 14:15:12",
                "endDate"=> "2023-01-15 15:15:12",
                "duration"=> 60,
                "isHotSale"=> 1,
                'products' => $formattedProducts
            ];

            return response()->json([
                'success'=> true,
                'data'=>$formattedData
            ],200);

        }

        return response()->json([
            'success'=> false,
            'data'=>null
        ],200);
    }
    public function getLandingPageCategories()
    {
        $isFeatured = Category::where('isFeatured', 1)->get();
        $isMenuFeatured = Category::where('isMenuFeatured', 1)->get();

        return response()->json([
            'success'=> true,
            'isFeatured'=>$isFeatured,
            'isMenuFeatured'=>$isMenuFeatured
        ],200);
    }

    public function getLandingPageProducts()
    {
        $isFeatured = MainProduct::where('isFeatured', 1)->where('isAvailable', 1)->with('group')->get();
        $isNew = MainProduct::where('isNew', 1)->where('isAvailable', 1)->with('group')->get();
        $formattedFeatureProducts = [];
        $formattedNewProducts = [];


        foreach($isFeatured as $value){
            $product = $value;
            unset($product['description']);
            unset($product['brief_description']);
            unset($product['averageBuyingPrice']);
            unset($product['unit']);
            unset($product['openingQuantity']);
            unset($product['openingUnitPrice']);
            $product['groupeName'] = $value->group?$value->group->groupName:'';
            array_push($formattedFeatureProducts, $product);
        }

        foreach($isNew as $value){
            $product = $value;
            unset($product['description']);
            unset($product['brief_description']);
            unset($product['averageBuyingPrice']);
            unset($product['unit']);
            unset($product['openingQuantity']);
            unset($product['openingUnitPrice']);
            unset($product['isHotDeal']);
            unset($product['isFeatured']);
            $product['groupeName'] = $value->group?$value->group->groupName:'';
            array_push($formattedNewProducts, $product);
        }

        return response()->json([
            'success'=> true,
            'isFeatured'=>$formattedFeatureProducts,
            'isNew'=>$formattedNewProducts
        ],200);
    }

    public function getMailingList()
    {
        $data = WebsiteMailingList::get();
        return response()->json([
            'success'=> true,
            'data'=>$data
        ],200);
    }
    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'email|required|unique:website_mailing_lists,email',
        ]);
        $user = WebsiteMailingList::create([
            'email' => $request->email,
        ]);
        return response()->json([
            'success'=> true,
            'user'=>$user
        ],200);
    }

    public function getArticles()
    {
        $data = WebsiteArticle::get();
        // return date('l jS \of F Y h:i:s A', strtotime($data[0]->created_at));
        $formattedData=[];
        foreach($data as $value){
            $value['date'] =  date('l jS \of F Y h:i:s A', strtotime($value->created_at));
            array_push($formattedData,$value);
        }
        return response()->json([
            'success'=> true,
            'data'=>$formattedData
        ],200);
    }


    //For wishList
    public function getWishlist(Request $request){
        $user_id = Auth::user()->id;
        $data =  Wishlist::where('userId', $user_id)->with('product')->get();

        $formattedData = [];
        foreach($data as $value){
            $product['id'] = $value['id'];
            $product['productName'] = $value->product->productName;
            $product['productImage'] = $value->product->productImage;
            $product['sellingPrice'] = $value->product->sellingPrice;
            $product['stock'] = $value->product->stream_socket_client;

            unset($product['product']);

            array_push($formattedData, $product);
        }
        return response()->json([
            'success'=> true,
            'data'=>$formattedData,
        ],200);
    }
    public function addWishList(Request $request){
        $request['userId'] = Auth::user()->id;
        $wishList = Wishlist::where('userId',$request['userId'])->where('productId',$request['productId'])->first();
        if($wishList){
            return $wishList;
        }
        return Wishlist::create([
            'userId'=>$request['userId'],
            'productId'=>$request['productId']
        ]);
    }
    public function removeWishList($id)
    {
        $remove = WishList::where('id', $id)->delete();
        return response()->json(['msg' => 'Removed', 'status' => $remove], 200);

    }
}
