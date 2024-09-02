<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductExitDetail;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Count total products and users
        $productCount = Product::count();
        $userCount = User::count();

        // Get current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Get the selected period, year, and month from the request
        $period = $request->input('period', 'month'); // Default to month
        $year = $request->input('year', $currentYear);
        $month = $request->input('month', $currentMonth);

        // Initialize the query for product exit details
        $query = ProductExitDetail::select('product_exits_detail.*', 'product_entries_detail.price as entry_price')
            ->join('product_entries_detail', 'product_exits_detail.product_entry_detail_id', '=', 'product_entries_detail.id');

        // Prepare to collect product exits
        $productExits = $query->get();

        // Calculate net income and prepare income data
        $netIncome = 0;
        $monthlyIncome = array_fill(1, 12, 0); // For monthly income
        $yearlyIncome = []; // For yearly income
        $dailyIncome = []; // For daily income

        foreach ($productExits as $exit) {
            $revenue = $exit->total;
            $cost = $exit->quantity * $exit->entry_price;
            $profit = $revenue - $cost;

            if ($profit > 0) {
                $netIncome += $profit;

                // Get date components from the exit date
                $exitDate = Carbon::parse($exit->exit_date);
                $dayKey = $exitDate->day;
                $monthKey = $exitDate->month;
                $yearKey = $exitDate->year;

                // Calculate monthly income for the selected year
                if ($yearKey == $year) {
                    $monthlyIncome[$monthKey] += $profit; // Sum profits by month
                }

                // Yearly income for the selected year
                if ($period == 'year' && $yearKey == $year) {
                    $yearlyIncome[$yearKey] = ($yearlyIncome[$yearKey] ?? 0) + $profit;
                }

                // All time income
                if ($period == 'all') {
                    $yearlyIncome[$yearKey] = ($yearlyIncome[$yearKey] ?? 0) + $profit;
                }

                // Daily income for the selected month and year
                if ($period == 'month' && $yearKey == $year && $monthKey == $month) {
                    $dailyIncome[$dayKey] = ($dailyIncome[$dayKey] ?? 0) + $profit;
                }
            }
        }

        // Calculate top exited products based on the selected period
        $topExitedProductsQuery = ProductExitDetail::select(
            'product.id as product_id',
            'product.name as product_name',
            DB::raw('SUM(product_exits_detail.quantity) as total_quantity')
        )
            ->join('product_entries_detail', 'product_exits_detail.product_entry_detail_id', '=', 'product_entries_detail.id')
            ->join('product', 'product_entries_detail.product_id', '=', 'product.id');

        if ($period == 'month') {
            $topExitedProductsQuery->whereYear('product_exits_detail.exit_date', $year)
                ->whereMonth('product_exits_detail.exit_date', $month);
        } elseif ($period == 'year') {
            $topExitedProductsQuery->whereYear('product_exits_detail.exit_date', $year);
        }
        // For 'all' period, we don't need to add any date constraints

        $topExitedProducts = $topExitedProductsQuery
            ->groupBy('product.id', 'product.name')
            ->orderByDesc('total_quantity')
            ->take(5)  // Get top 5 products
            ->get();

        // Get distinct years for the dropdown
        $years = ProductExitDetail::selectRaw("strftime('%Y', exit_date) as year")
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Return the view with the collected data
        return view('pages.dashboard', compact(
            'productCount',
            'userCount',
            'netIncome',
            'period',
            'year',
            'month',
            'years',
            'currentYear',
            'currentMonth',
            'monthlyIncome',
            'yearlyIncome',
            'dailyIncome',
            'topExitedProducts'
        ));
    }
}