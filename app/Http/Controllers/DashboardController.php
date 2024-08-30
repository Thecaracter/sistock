<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $userCount = User::count();
        return view('pages.dashboard', compact('productCount', 'userCount'));
    }
}
