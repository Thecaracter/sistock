<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductExitDetail;
use App\Models\ProductEntryDetail;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $userCount = User::count();
        // Mengambil total pendapatan dari ProductExitDetail
        $totalRevenue = ProductExitDetail::sum('total');

        // Mengambil total biaya dari ProductEntryDetail
        $totalCost = ProductEntryDetail::sum('total');

        // Menghitung penghasilan bersih
        $netIncome = $totalRevenue - $totalCost;
        return view('pages.dashboard', compact('productCount', 'userCount', 'netIncome'));
    }
}
