<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Executive Summary for Owner / Leadership
     */
    public function dashboardSummary()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Today metrics
        $todayOrders = Order::where('status', 'completed')
            ->whereDate('created_at', $today);
        $todayRevenue = (float) $todayOrders->sum('total_price');
        $todayOrderCount = $todayOrders->count();

        // This month metrics
        $monthOrders = Order::where('status', 'completed')
            ->where('created_at', '>=', $startOfMonth);
        $monthRevenue = (float) $monthOrders->sum('total_price');
        $monthOrderCount = $monthOrders->count();

        // All-time metrics
        $totalRevenue = (float) Order::where('status', 'completed')->sum('total_price');
        $totalOrdersCount = Order::where('status', 'completed')->count();
        $totalProductsCount = Product::where('is_active', true)->count();

        // Payment method breakdown (this month)
        $paymentMethods = Order::where('status', 'completed')
            ->where('created_at', '>=', $startOfMonth)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as total_amount'))
            ->groupBy('payment_method')
            ->get();

        // Top 5 Best Selling Products
        $bestSellers = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->image_url = $item->image ? url('storage/' . $item->image) : null;
                return $item;
            });

        // Recent 5 transactions
        $recentOrders = Order::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard summary retrieved successfully',
            'data' => [
                'today' => [
                    'revenue' => $todayRevenue,
                    'order_count' => $todayOrderCount,
                ],
                'this_month' => [
                    'revenue' => $monthRevenue,
                    'order_count' => $monthOrderCount,
                ],
                'all_time' => [
                    'revenue' => $totalRevenue,
                    'order_count' => $totalOrdersCount,
                    'total_products' => $totalProductsCount,
                ],
                'payment_methods' => $paymentMethods,
                'best_sellers' => $bestSellers,
                'recent_orders' => $recentOrders,
            ],
        ]);
    }

    /**
     * Revenue timeline report for charts
     */
    public function revenueChart(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(6)->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $dailyStats = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Revenue chart data retrieved successfully',
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'timeline' => $dailyStats,
            ],
        ]);
    }
}
