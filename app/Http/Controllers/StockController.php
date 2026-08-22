<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    // GET /stock — ภาพรวมสต็อกทั้งหมด + สินค้าใกล้หมด
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->search($request->get('q'))
            ->when($request->boolean('low_stock_only'), fn($q) => $q->whereColumn('stock_quantity', '<=', 'reorder_level'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $lowStockProducts = Product::where('status', 'active')
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();

        return view('stock.index', compact('products', 'lowStockProducts'));
    }

    // POST /products/{product}/stock/adjust — ปรับปรุงจำนวนสต็อก (Feature: ปรับปรุงจำนวนสต็อก)
    public function adjust(Request $request, Product $product)
    {
        $data = $request->validate([
            'type'     => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason'   => ['required', 'string', 'max:255'],
        ]);

        // แปลงทิศทางจำนวนตามประเภท: in = บวก, out = ลบ, adjustment ให้ผู้ใช้เลือกทิศทางเองผ่านช่อง direction
        $direction = $request->input('direction', $data['type'] === 'out' ? 'decrease' : 'increase');
        $delta = $direction === 'decrease' ? -abs($data['quantity']) : abs($data['quantity']);

        if ($product->stock_quantity + $delta < 0) {
            return back()->with('error', "สต็อกไม่พอ (คงเหลือ {$product->stock_quantity} ชิ้น ไม่สามารถตัดออก {$data['quantity']} ชิ้นได้)");
        }

        DB::transaction(function () use ($product, $data, $delta) {
            $product->increment('stock_quantity', $delta);
            $product->refresh();

            StockMovement::create([
                'product_id'    => $product->id,
                'type'          => $data['type'],
                'quantity'      => $delta,
                'balance_after' => $product->stock_quantity,
                'reason'        => $data['reason'],
                'created_by'    => auth()->user()->name ?? 'แอดมิน',
            ]);
        });

        return back()->with('success', 'ปรับปรุงสต็อกเรียบร้อยแล้ว ยอดคงเหลือปัจจุบัน: ' . $product->fresh()->stock_quantity . ' ชิ้น');
    }
}