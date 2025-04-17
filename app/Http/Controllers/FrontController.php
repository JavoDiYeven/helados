<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function inicio(){
        return view('frontend.inicio');
    }
    public function productos(){
        return view('frontend.productos');
    }
    public function contacto(){
        return view('frontend.contacto');
    }
    public function nosotros(){
        return view('frontend.nosotros');
    }
}
