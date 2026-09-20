<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Today metrics
        $todayRevenue = (float) Order::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('total_price');
        $todayOrdersCount = Order::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->count();

        // Month metrics
        $monthRevenue = (float) Order::where('status', 'completed')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_price');
        $monthOrdersCount = Order::where('status', 'completed')
            ->where('created_at', '>=', $startOfMonth)
            ->count();

        // Totals
        $totalProducts = Product::where('is_active', true)->count();
        $totalCategories = Category::count();

        // Recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top 5 Best Sellers
        $bestSellers = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->select(
                'products.name',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Last 7 days revenue for chart
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $rev = Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $last7Days->push([
                'date' => Carbon::parse($date)->translatedFormat('d M'),
                'revenue' => (float) $rev,
            ]);
        }

        return view('pages.dashboard', compact(
            'todayRevenue',
            'todayOrdersCount',
            'monthRevenue',
            'monthOrdersCount',
            'totalProducts',
            'totalCategories',
            'recentOrders',
            'bestSellers',
            'last7Days'
        ));
    }
}
