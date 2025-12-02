<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index($name){
        return view('home', ['user' => $name]);  // Corrected
    }

    public function home(){
        if(View::exists('welcome')){
            return view('welcome');
        }else{
            echo 'Not Found Mr. Junaid';
        }
    }
}
