<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Financial & Sales Report for Cafe Owner / Admin
     */
    public function finance(Request $request)
    {
        // Default to current month if not specified
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $query = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Filter by cashier
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Clone query for aggregation metrics
        $totalGross = (float) (clone $query)->sum('subtotal');
        $totalDiscount = (float) (clone $query)->sum('discount');
        $totalTax = (float) (clone $query)->sum('tax');
        $totalServiceCharge = (float) (clone $query)->sum('service_charge');
        $totalNetRevenue = (float) (clone $query)->sum('total_price');
        $totalTransactions = (clone $query)->count();
        $totalItemsSold = (int) (clone $query)->sum('total_item');
        $averageOrderValue = $totalTransactions > 0 ? ($totalNetRevenue / $totalTransactions) : 0;

        // Breakdown by payment method
        $paymentMethods = (clone $query)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as total_amount'))
            ->groupBy('payment_method')
            ->get();

        // Daily breakdown in date range
        $dailySummary = (clone $query)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_item) as total_items'),
                DB::raw('SUM(discount) as total_discount'),
                DB::raw('SUM(tax) as total_tax'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Top products sold in this period
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as qty_sold'),
                DB::raw('SUM(order_items.subtotal) as total_amount')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('qty_sold')
            ->take(10)
            ->get();

        $cashiers = User::all();

        return view('pages.reports.finance', compact(
            'startDate',
            'endDate',
            'totalGross',
            'totalDiscount',
            'totalTax',
            'totalServiceCharge',
            'totalNetRevenue',
            'totalTransactions',
            'totalItemsSold',
            'averageOrderValue',
            'paymentMethods',
            'dailySummary',
            'topProducts',
            'cashiers'
        ));
    }
}
