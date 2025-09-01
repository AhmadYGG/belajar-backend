<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Buat order baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","character_name","payment","items"},
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="character_name", type="string", example="Antonio_Rudriger"),
     *             @OA\Property(property="payment", type="string", example="OVO"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="PremiumPackage"),
     *                     @OA\Property(property="qty", type="integer", example=2),
     *                     @OA\Property(property="price", type="number", example=25000)
     *                 )
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(property="order_id", type="integer", example=10),
     *             @OA\Property(property="total", type="number", example=50000)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */


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

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Lihat detail order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail order dengan item",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="character_name", type="string", example="Antonio_Rudriger"),
     *             @OA\Property(property="payment", type="string", example="OVO"),
     *             @OA\Property(property="total_price", type="number", example=50000),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="PremiumPackage"),
     *                     @OA\Property(property="qty", type="integer", example=2),
     *                     @OA\Property(property="price", type="number", example=25000)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */

    // menampilkan orderan
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return response()->json($order);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}/receipt",
     *     tags={"Orders"},
     *     summary="Download order receipt (PDF)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File PDF receipt",
     *         @OA\MediaType(
     *             mediaType="application/pdf"
     *         )
     *     ),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */

    // menampilkan struk pembayaran
    public function receipt($id)
    {
        $order = Order::with('items')->findOrFail($id);

        $pdf = PDF::loadView('pdf.receipt', compact('order'))
            ->setPaper('A5', 'portrait');

        return $pdf->download("receipt_order_{$order->id}.pdf");
    }
}
