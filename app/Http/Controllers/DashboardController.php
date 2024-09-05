<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Store;
use App\Models\Journal;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\FranchiseOwner;
use App\Jobs\ExportFinancialData;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ExportBrandFinancialData;
use Illuminate\Support\Facades\Request as Req;
use App\Models\Product; // Make sure you import the Product model

class DashboardController extends Controller
{
    public function stores()
    {
        $user = Auth::user();
        $franchiseOwner = FranchiseOwner::where('email', $user->email)->first();
        $stores = Store::where('franchise_owner_id', $franchiseOwner->id)->get();
        return view('stores', compact('stores'));
    }

    public function brands()
    {
        // Fetch stores for the specific franchise owner
        $user = Auth::user();
        $franchiseOwner = FranchiseOwner::where('email', $user->email)->first();
        $stores = Store::where('franchise_owner_id', $franchiseOwner->id)->get();

        // Fetch distinct brands associated with these stores
        $brands = Brand::whereIn('id', $stores->pluck('brand_id'))->get();

        // Calculate total revenue for each brand
        $brands->each(function ($brand) {
            $brand->total_profit = $brand->total_profit; // Adjust this if necessary
        });

        // Order brands by total_profit in descending order
        $brands = $brands->sortByDesc('total_profit');

        return view('brands', compact('brands'));
    }

    public function stats($id)
    {
        $storeId = $id;

        // Fetch and order journals by date
        $journals = Journal::where('store_id', $storeId)
            ->orderBy('date') // Order by date in ascending order. Use 'desc' for descending.
            ->get();

        // Calculate totals
        $totalRevenue = $journals->sum('revenue');
        $totalFoodCost = $journals->sum('food_cost');
        $totalLaborCost = $journals->sum('labor_cost');
        $totalProfit = $journals->sum('profit');

        return view('stats', compact('journals', 'totalRevenue', 'totalFoodCost', 'totalLaborCost', 'totalProfit', 'storeId'));
    }

    public function brand_stats($id)
    {
        $brand = Brand::findOrFail($id);
        $stores = $brand->stores;
        $storeIds = $stores->pluck('id');

        $journals = Journal::whereIn('store_id', $storeIds)->get();
        $totalRevenue = $journals->sum('revenue');
        $totalFoodCost = $journals->sum('food_cost');
        $totalLaborCost = $journals->sum('labor_cost');
        $totalProfit = $journals->sum('profit');

        return view('brand_stats', compact('journals', 'totalRevenue', 'totalFoodCost', 'totalLaborCost', 'totalProfit', 'brand'));
    }

    public function export(Request $request)
    {
        $storeId = $request->input('store_id');
        $userEmail = auth()->user()->email;

        // Dispatch the job
        ExportFinancialData::dispatch($storeId, $userEmail);

        return redirect()->back()->with('status', 'Export in progress. You will receive an email with a download link once it is complete.');
    }
    public function brand_export(Request $request)
    {
        $brandId = $request->input('brand_id');
        $userEmail = auth()->user()->email;

        ExportBrandFinancialData::dispatch($brandId, $userEmail);

        return redirect()->back()->with('status', 'Export in progress. You will receive an email with a download link once it is complete.');
    }
}
