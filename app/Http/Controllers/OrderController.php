<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function track(Request $request)
    {
        if ($request->has('order_id') && $request->has('phone')) {
            $order = Order::with('orderItems.item')
                ->where('id', $request->order_id)
                ->where('customer_phone', $request->phone)
                ->first();

            if (!$order) {
                return redirect()->route('orders.track')->with('error', 'We could not find that order. Check the order number and phone number.');
            }

            return view('orders.show', compact('order'));
        }

        if ($request->has('phone')) {
            $orders = Order::where('customer_phone', $request->phone)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($orders->isEmpty()) {
                return redirect()->back()->with('error', 'No orders were found for this phone number.');
            }

            return view('orders.track', compact('orders'))->with('phone', $request->phone);
        }

        return view('orders.track');
    }
}
