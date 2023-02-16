<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class AdminController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.index', ['categories' => $categories]);
    }
}