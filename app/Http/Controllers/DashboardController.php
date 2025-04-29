<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $lowStockProducts = Product::getLowStockProducts();
        $lowStockCount = $lowStockProducts->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        
        $categories = Category::withCount('products')->get();
        
        return view('dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'lowStockProducts', 
            'lowStockCount', 
            'outOfStockCount',
            'categories'
        ));
    }
}
