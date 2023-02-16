<?php

namespace App\Http\Controllers;
use App\Models\MainProduct;
use App\Models\Category;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function getCategories()
    {
        $category = Group::with('category')->get();
        return response()->json([
            'success'=> true,
            'data'=>$category,
        ],200);
    }

    public function getProducts()
    {
        $product = MainProduct::get();
        $formattedData = [];
        foreach($data as $value){
            $product = $value;
            unset($product['description']);
            unset($product['brief_description']);
            unset($product['averageBuyingPrice']);
            unset($product['unit']);
            unset($product['isFeatured']);
            unset($product['adminDiscount']);
            unset($product['appDiscount']);
            unset($product['openingQuantity']);
            unset($product['openingUnitPrice']);

            $product['catName'] = $value->group->groupName;
            $product['brandName'] = $value->brand->name;
            $product['subcatName'] = $value->category->catName;
            unset($product['group']);
            unset($product['brand']);
            unset($product['category']);

            array_push($formattedData, $product);
        }
        return response()->json([
            'success'=> true,
            'product'=>$product,
        ],200);
    }

    public function getTags()
    {
        $tag = Tag::get();
        return response()->json([
            'success'=> true,
            'data'=>$tag,
        ],200);
    }

    public function productList(Request $request)
    {
        $search = $request->search;
        $brandId = $request->brand;
        $groupName=  $request->group;
        $catName=  $request->category;
        $tagName=  $request->tag;
        $minPrice = $request->minPrice;
        $maxPrice = $request->maxPrice;
        $default = $request->default;
        $order=$request->order;
        // return $groupName;
        // $desc = $request->desc;

        $limit = $request->limit? $request->limit : 20;

        $query =  MainProduct::with('group','category','brand','productTag')->where('is_archived',0);

        // $data = MainProduct::whereBetween('sellingPrice', [200, 400])->get();

        if($search){
            $query->where(function ($queryy) use ($search){
                $queryy->orWhere('productName', 'like', "%$search%")
                 ->orWhere('model', 'like', "%$search%");
            });
        }
        if($groupName){
            $group = explode(",",$request->group);
            $query->whereIn('groupId', $group);
            // $query->whereIn('groupId', array($groupName));
        }
        if($brandId){
            $query->where('brandId', $brandId);
        }
        if($catName){
            $query->whereIn('categoryId', explode(",",$catName));
        }
        if($minPrice && $maxPrice){
            $query->whereBetween('sellingPrice',[$minPrice, $maxPrice]);
        }
        if($tagName){
            $query->whereHas('productTag',function($queryy) use ($tagName){
                $queryy->whereIn('tagId',explode(',',$tagName));
            });
        }
        if($default && $order){
            $query->orderBy($default,$order);
        }
        $data = $query->limit($limit)->get();

        $formattedData = [];
        foreach($data as $value){
            $product = $value;
            unset($product['description']);
            unset($product['brief_description']);
            unset($product['averageBuyingPrice']);
            unset($product['unit']);
            unset($product['isFeatured']);
            unset($product['adminDiscount']);
            unset($product['appDiscount']);
            unset($product['openingQuantity']);
            unset($product['openingUnitPrice']);

            $product['groupeName'] = $value->group->groupName;
            $product['brandName'] = $value->brand->name;
            $product['catName'] = $value->category->catName;
            unset($product['group']);
            unset($product['brand']);
            unset($product['category']);

            if($product['discount'] > 0){
                $product['discountedPrice'] = floor(($product['sellingPrice']*$product['discount'])/100);
            }
            array_push($formattedData, $product);

        }
        return response()->json([
            'success'=> true,
            'data'=>$formattedData,
        ],200);
    }

    // public function priceRange(Request $request)
    // {

    //     $range = [$request->min_price, $request->max_price];
    //     $data = MainProduct::whereBetween('sellingPrice', $range)->get();

    //     // $data = MainProduct::whereBetween('sellingPrice', [200, 400])->get();

    //     return response()->json([
    //         'success'=> true,
    //         'data'=>$data
    //     ],200);
    // }
}
