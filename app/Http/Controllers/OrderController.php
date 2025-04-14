<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Order created"),
     *     @OA\Response(response=400, description="Not enough stock")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($validated['products'] as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            if ($product->stock_quantity < $productData['quantity']) {
                return response()->json(['message' => "Not enough stock for product {$product->name}"], 400);
            }
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => 0,
            'status' => 'Pending',
        ]);

        $totalPrice = 0;

        foreach ($validated['products'] as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $totalPrice += $product->price * $productData['quantity'];

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'price' => $product->price,
            ]);

            $product->decrement('stock_quantity', $productData['quantity']);
        }

        $order->total_price = $totalPrice;
        $order->save();

        return response()->json(['order' => $order], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/my-orders",
     *     tags={"Orders"},
     *     summary="View customer's own orders",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of orders")
     * )
     */
    public function myOrders()
    {
        return Order::where('user_id', auth()->id())->with('items.product')->get();
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Admin view all orders",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="All orders with users and products")
     * )
     */
    public function allOrders()
    {
        return Order::with('items.product', 'user')->get();
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}/status",
     *     tags={"Orders"},
     *     summary="Update order status (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "cancelled"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Order updated")
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return response()->json(['order' => $order]);
    }
}
