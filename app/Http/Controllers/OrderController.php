<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // memasukkan item yang dibeli ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'          => 'required|string',
            'character_name'    => 'required|string',
            'payment'           => 'required|string',
            'items'             => 'required|array',
            'items.*.name'      => 'required|string',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.price'     => 'required|numeric|min:0',
        ]);

        // hitung total price
        $total = collect($validated['items'])->sum(fn($item) => $item['qty'] * $item['price']);

        // simpan order
        $order = Order::create([
            'user_id'        => auth()->id(),
            'username' => $validated['username'],
            'character_name'    => $validated['character_name'],
            'payment'        => $validated['payment'],
            'total_price'    => $total,
        ]);

        // simpan order items
        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'name'     => $item['name'],
                'qty'      => $item['qty'],
                'price'    => $item['price'],
            ]);
        }

        // memberikan response berupa json
        return response()->json([
            'message' => 'Order created successfully',
            'order_id' => $order->id,
            'total' => $total,
        ]);
    }

    // menampilkan orderan
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return response()->json($order);
    }

    // menampilkan struk pembayaran
    public function receipt($id)
    {
        $order = Order::with('items')->findOrFail($id);

        $pdf = PDF::loadView('pdf.receipt', compact('order'))
            ->setPaper('A5', 'portrait');

        return $pdf->download("receipt_order_{$order->id}.pdf");
    }
}
