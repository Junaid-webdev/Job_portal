<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This method will show our home page//
    public function index(){


      $categories =  Category::where('status',1)->orderBy('name','ASC')->take(8)->get();

        return view('front.home',[
            'categories' =>  $categories
        ]);
    }
  
}
