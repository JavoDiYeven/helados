<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackController extends Controller
{
    public function backend()
    {
        return view('backend.inicio');
    }
    
    public function inicio()
    {
        return view('backend.inicio');
    }
    public function productos()
    {
        return view('backend.productos');
    }
    public function reportes()
    {
        return view('backend.reportes');
    }
}
