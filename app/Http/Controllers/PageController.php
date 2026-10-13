<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PageController extends Controller
{
    public function showHome() {
        $products = Product::all();
        return view('home', ['products' => $products]);
    }

    public function showContact() {
        return view('contact');
    }
 
    public function showDashboard() {
        return view('dashboard-admin');
    }
}
