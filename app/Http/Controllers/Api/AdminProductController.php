<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GiftOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // List semua produk
    public function index()
    {
        $products = Product::orderBy('sort_order')->orderBy('name')->get()
            ->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'description' => $p->description,
                'image_url'   => $p->image_path ? asset('storage/' . $p->image_path) : null,
                'price'       => $p->price,
                'stock'       => $p->stock,
                'is_active'   => $p->is_active,
                'sort_order'  => $p->sort_order,
                'orders_count'=> $p->giftOrders()->where('status', 'paid')->count(),
            ]);

        return response()->json(['success' => true, 'products' => $products]);
    }

    // Buat produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|integer|min:1000',
            'stock'       => 'required|integer|min:1',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image']);

        $product = Product::create($validated);

        return response()->json(['success' => true, 'product' => $product], 201);
    }

    // Update produk
    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'sometimes|required|integer|min:1000',
            'stock'       => 'sometimes|required|integer|min:1',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image']);

        $product->update($validated);

        return response()->json(['success' => true, 'product' => $product->fresh()]);
    }

    // Hapus produk
    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Produk dihapus']);
    }

    // List semua gift orders (admin view)
    public function allOrders(Request $request)
    {
        $status = $request->query('status');

        $query = GiftOrder::with(['product:id,name', 'invitation:id,bride_name,groom_name,unique_url'])
            ->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20)->through(fn($o) => [
            'id'              => $o->id,
            'order_code'      => $o->order_code,
            'product_name'    => $o->product->name,
            'invitation'      => $o->invitation->bride_name . ' & ' . $o->invitation->groom_name,
            'buyer_name'      => $o->buyer_name,
            'buyer_email'     => $o->buyer_email,
            'buyer_phone'     => $o->buyer_phone,
            'buyer_message'   => $o->buyer_message,
            'amount'          => $o->amount,
            'status'          => $o->status,
            'paid_at'         => $o->paid_at?->toIso8601String(),
            'shipping_status' => $o->shipping_status,
            'tracking_number' => $o->tracking_number,
            'shipping_address'=> $o->shipping_address,
        ]);

        return response()->json(['success' => true, 'orders' => $orders]);
    }

    // Update shipping status (admin)
    public function updateShipping(Request $request, int $orderId)
    {
        $order = GiftOrder::findOrFail($orderId);

        $validated = $request->validate([
            'shipping_status' => 'required|in:pending,processing,shipped,delivered',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $updates = $validated;
        if ($validated['shipping_status'] === 'shipped' && !$order->shipped_at) {
            $updates['shipped_at'] = now();
        }
        if ($validated['shipping_status'] === 'delivered' && !$order->delivered_at) {
            $updates['delivered_at'] = now();
        }

        $order->update($updates);

        return response()->json(['success' => true, 'order' => $order->fresh()]);
    }
}
