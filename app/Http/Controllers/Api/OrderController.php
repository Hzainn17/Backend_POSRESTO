<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Get list of orders / transaction history
     */
    public function index(Request $request)
    {
        $query = Order::with(['user:id,name', 'orderItems.product:id,name,price,image']);

        // Filter by date range or single date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by cashier (user_id)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $perPage = $request->input('per_page', 15);
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Orders retrieved successfully',
            'data' => $orders,
        ]);
    }

    /**
     * Checkout new order from POS Flutter
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'nullable|string|max:100',
            'table_number'   => 'nullable|string|max:50',
            'payment_method' => 'required|in:cash,qris,transfer,debit',
            'payment_amount' => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'service_charge' => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.notes'      => 'nullable|string|max:255',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                // Collect product IDs
                $productIds = collect($request->items)->pluck('product_id')->toArray();
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $subtotal = 0;
                $totalItem = 0;
                $itemsToCreate = [];

                foreach ($request->items as $itemData) {
                    $productId = $itemData['product_id'];
                    $qty = $itemData['quantity'];
                    $product = $products->get($productId);

                    if (!$product) {
                        throw new \Exception("Produk dengan ID {$productId} tidak ditemukan.");
                    }

                    // Check stock
                    if ($product->stock < $qty) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi (sisa: {$product->stock}, diminta: {$qty}).");
                    }

                    $itemSubtotal = $product->price * $qty;
                    $subtotal += $itemSubtotal;
                    $totalItem += $qty;

                    // Deduct product stock
                    $product->decrement('stock', $qty);

                    $itemsToCreate[] = [
                        'product_id' => $productId,
                        'quantity'   => $qty,
                        'price'      => $product->price,
                        'subtotal'   => $itemSubtotal,
                        'notes'      => $itemData['notes'] ?? null,
                    ];
                }

                $tax = (float) ($request->tax ?? 0);
                $discount = (float) ($request->discount ?? 0);
                $serviceCharge = (float) ($request->service_charge ?? 0);
                $totalPrice = max(0, $subtotal + $tax + $serviceCharge - $discount);

                $paymentAmount = (float) $request->payment_amount;
                if ($request->payment_method === 'cash' && $paymentAmount < $totalPrice) {
                    throw new \Exception("Nominal pembayaran tunai kurang dari total tagihan.");
                }

                $changeAmount = ($request->payment_method === 'cash')
                    ? max(0, $paymentAmount - $totalPrice)
                    : 0;

                // Generate unique transaction number: TRX-YYYYMMDD-XXXX
                $datePrefix = date('Ymd');
                $randomSuffix = strtoupper(Str::random(4));
                $transactionNumber = 'TRX-' . $datePrefix . '-' . time() . '-' . $randomSuffix;

                $order = Order::create([
                    'transaction_number' => $transactionNumber,
                    'user_id'            => $request->user()->id,
                    'customer_name'      => $request->customer_name,
                    'table_number'       => $request->table_number,
                    'subtotal'           => $subtotal,
                    'tax'                => $tax,
                    'discount'           => $discount,
                    'service_charge'     => $serviceCharge,
                    'total_price'        => $totalPrice,
                    'total_item'         => $totalItem,
                    'payment_method'     => $request->payment_method,
                    'payment_amount'     => $paymentAmount,
                    'change_amount'      => $changeAmount,
                    'status'             => 'completed',
                    'notes'              => $request->notes,
                ]);

                foreach ($itemsToCreate as $item) {
                    $order->orderItems()->create($item);
                }

                return $order;
            });

            // Load full relationships for receipt response
            $order->load(['user:id,name', 'orderItems.product:id,name,price,image']);

            return response()->json([
                'status'  => 'success',
                'message' => 'Order created successfully',
                'data'    => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show single order detail for receipt printing
     */
    public function show($id)
    {
        $order = Order::with(['user:id,name', 'orderItems.product:id,name,price,image'])->find($id);

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Order details retrieved successfully',
            'data'    => $order,
        ]);
    }
}
