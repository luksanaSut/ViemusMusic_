<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->orderBy('name')->get();
        return view('product-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        ProductCategory::create($data);

        return back()->with('success', 'เพิ่มหมวดหมู่สินค้าเรียบร้อยแล้ว');
    }

    public function toggleActive(ProductCategory $productCategory)
    {
        $productCategory->update(['is_active' => !$productCategory->is_active]);
        return back()->with('success', 'อัปเดตสถานะแล้ว');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->exists()) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากยังมีสินค้าผูกกับหมวดหมู่นี้อยู่');
        }
        $productCategory->delete();
        return back()->with('success', 'ลบหมวดหมู่แล้ว');
    }
}