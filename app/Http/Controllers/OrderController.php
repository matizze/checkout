<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(): View
    {
        $orders = Order::with(['customer', 'payment'])
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $order->load(['customer', 'items.product', 'payment']);

        return view('orders.show', compact('order'));
    }
}
