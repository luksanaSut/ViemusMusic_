<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // GET /products
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->search($request->get('q'))
            ->category($request->get('category_id'))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->boolean('low_stock_only'), fn($q) => $q->whereColumn('stock_quantity', '<=', 'reorder_level'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $categories = ProductCategory::orderBy('name')->get();
        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'reorder_level')->where('status', 'active')->count();

        return view('products.index', compact('products', 'categories', 'lowStockCount'));
    }

    // GET /products/create
    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $nextSku = Product::generateSku();

        return view('products.create', compact('categories', 'nextSku'));
    }

    // POST /products
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['sku'] = Product::generateSku();
        $data['stock_quantity'] = 0;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $initialStock = $data['initial_stock'] ?? 0;
        unset($data['initial_stock']);

        $product = null;
        DB::transaction(function () use ($data, $initialStock, &$product) {
            $product = Product::create($data);

            if ($initialStock > 0) {
                $product->update(['stock_quantity' => $initialStock]);
                StockMovement::create([
                    'product_id'    => $product->id,
                    'type'          => 'in',
                    'quantity'      => $initialStock,
                    'balance_after' => $initialStock,
                    'reason'        => 'สต็อกเริ่มต้นตอนเพิ่มสินค้า',
                    'created_by'    => auth()->user()->name ?? 'แอดมิน',
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', "เพิ่มสินค้า \"{$product->name}\" เรียบร้อยแล้ว (รหัส {$product->sku})");
    }

    // GET /products/{product}/edit
    public function edit(Product $product)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    // PUT /products/{product}
    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();
        unset($data['initial_stock']); // แก้ไขสต็อกต้องทำผ่านหน้าปรับปรุงสต็อกแยกต่างหาก ไม่ใช่ตรงนี้

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว');
    }

    // DELETE /products/{product}
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'ลบสินค้าเรียบร้อยแล้ว');
    }

    // GET /products/{product} — ดูรายละเอียด + ประวัติการเคลื่อนไหวสต็อก
    public function show(Product $product)
    {
        $product->load('category');
        $product->setRelation(
            'stockMovements',
            $product->stockMovements()->orderByDesc('created_at')->limit(50)->get()
        );

        return view('products.show', compact('product'));
    }
}
