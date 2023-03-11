<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Category;
use App\Models\MainProduct;
use App\Models\Product;
use App\Models\Image;

use Validator;
class AddProductController extends Controller
{
    public function getGroup(Request $request){
        $limit = $request->limit;
        if($limit){
            $group = Group::limit($limit)->get();
        } else{
            $group = Group::get();
        }
        return response()->json([
            'success'=> true,
            'data'=>$group,
        ],200);
    }

    public function getCategory(Request $request){
        $group = $request->group;
        if($group){
            $category = Category::where('group_id', $request->group)->get();
        }
        else{
            $category = Category::get();
        }

        return response()->json([
            'success'=> true,
            'data'=>$category,
        ],200);
    }

    //image upload
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png',
        ]);
        $fileName = time() . '_' . $request->file->getClientOriginalName();
        $request->file->move('images', $fileName);
        return $fileName;
    }
    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteImageFromServer($fileName, false);
        return 'done';
    }
    public function deleteImageFromServer($fileName, $hasFullPath = false)
    {
        if (!$hasFullPath) {
            $filePath = public_path('images') .'\\'. $fileName;
            \Log::info($filePath);
        }
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }

    public function store(Request $request){
        $data = MainProduct::create($request->all());
         Product::create([
            'mproductId' => $data->id,
            'menuId' => $request->menuId,
            'productName' => $request->productName,
            'groupId' => $request->groupId,
            'categoryId' => $request->categoryId,
            'brandId' => $request->brandId,
            'sellingPrice' => $request->sellingPrice,
            'model' =>  $request->model,
            'unit' =>  $request->unit,
            'menuId' =>  $request->menuId,
            'variation' => 'variation',
            'date' => now(),
        ]);
        return response()->json([
            'success'=> true,
            'data'=>$data,
        ],201);
    }
    public function index(Request $request){
        $data = MainProduct::with('group', 'category')->limit($request->limit)->orderBy('id', 'desc')->get();
        return response()->json([
            'success'=> true,
            'data'=>$data,
        ],200);
    }
    
    public function destroy($id){
        $data = MainProduct::where('id', $id)->delete();
        $data = Product::where('mproductId', $id)->delete();
        return response()->json([
            'success'=> true,
            'data'=>$data,
        ],201);
    }
}
