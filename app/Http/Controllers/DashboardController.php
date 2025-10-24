<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        // Pendapatan
        $todayRevenue = Order::whereDate('transaction_time', now())->sum('total_price');
        $monthlyRevenue = Order::whereMonth('transaction_time', now()->month)
                            ->whereYear('transaction_time', now()->year)
                            ->sum('total_price');
        $avgOrderValue = Order::select(
                                DB::raw('MONTH(transaction_time) as month'),
                                DB::raw('AVG(total_price) as avg_order_value')
                            )
                            ->whereYear('transaction_time', date('Y'))
                            ->groupBy(DB::raw('MONTH(transaction_time)'))
                            ->get();

        $thisMonth = Carbon::now()->month;
        $avgOrderThisMonth = $avgOrderValue
            ->firstWhere('month', $thisMonth)
            ->avg_order_value ?? 0;

        // Produk terlaris
        $top_products = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as quantity_sold'), DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('quantity_sold')
            ->paginate(5);

        // Produk sepi
        $slow_products = DB::table('products')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->select('products.name', DB::raw('COALESCE(SUM(order_items.quantity),0) as quantity_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('quantity_sold', 'asc')
            ->paginate(5);

        // Stok menipis
        $low_stock = Product::where('stock', '<', 10)->get();

        // Grafik Penjualan Harian
        $salesTrend = Order::select(
                DB::raw('DATE(transaction_time) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->whereMonth('transaction_time', now()->month)
            ->groupBy(DB::raw('DATE(transaction_time)'))
            ->orderBy('date')
            ->get();

        $trendLabels = $salesTrend->pluck('date')->toArray();
        $trendData = $salesTrend->pluck('total')->toArray();

        // Metode pembayaran
        $paymentStats = Order::select('payment_method', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_method')
            ->get();

        $monthlyExpenditure = Expenditure::whereMonth('spent_at', now()->month)
            ->whereYear('spent_at', now()->year)
            ->sum('amount');

        return view('pages.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'todayRevenue',
            'avgOrderThisMonth',
            'monthlyRevenue',
            'top_products',
            'slow_products',
            'low_stock',
            'trendLabels',
            'trendData',
            'paymentStats',
            'monthlyExpenditure'
        ));
    }
}
