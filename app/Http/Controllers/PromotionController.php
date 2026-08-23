<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Models\Course;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // GET /promotions
    public function index(Request $request)
    {
        $promotions = Promotion::with(['courses', 'products'])
            ->when($request->filled('q'), fn($q) => $q->where(function ($qq) use ($request) {
                $qq->where('code', 'like', '%' . $request->q . '%')
                    ->orWhere('name', 'like', '%' . $request->q . '%');
            }))
            ->when($request->filled('scope'), fn($q) => $q->where('scope', $request->scope))
            ->when($request->filled('is_active'), fn($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($request->get('type') === 'coupon', fn($q) => $q->whereNotNull('code'))
            ->when($request->get('type') === 'promotion', fn($q) => $q->whereNull('code'))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('promotions.index', compact('promotions'));
    }

    // GET /promotions/create
    public function create()
    {
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('status', 'active')->orderBy('name')->get();

        return view('promotions.create', compact('courses', 'products'));
    }

    // POST /promotions
    public function store(StorePromotionRequest $request)
    {
        $data = $request->validated();
        $data['applies_to_all'] = $request->boolean('applies_to_all');

        $promotion = Promotion::create($data);

        if (!$promotion->applies_to_all) {
            if (in_array($promotion->scope, ['course', 'both'])) {
                $promotion->courses()->sync($data['course_ids'] ?? []);
            }
            if (in_array($promotion->scope, ['product', 'both'])) {
                $promotion->products()->sync($data['product_ids'] ?? []);
            }
        }

        return redirect()->route('promotions.index')->with('success', 'เพิ่มโปรโมชัน/คูปองเรียบร้อยแล้ว');
    }

    // GET /promotions/{promotion}/edit
    public function edit(Promotion $promotion)
    {
        $promotion->load(['courses', 'products']);
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('status', 'active')->orderBy('name')->get();

        return view('promotions.edit', compact('promotion', 'courses', 'products'));
    }

    // PUT /promotions/{promotion}
    public function update(UpdatePromotionRequest $request, Promotion $promotion)
    {
        $data = $request->validated();
        $data['applies_to_all'] = $request->boolean('applies_to_all');

        $promotion->update($data);

        if (!$promotion->applies_to_all) {
            if (in_array($promotion->scope, ['course', 'both'])) {
                $promotion->courses()->sync($data['course_ids'] ?? []);
            } else {
                $promotion->courses()->sync([]);
            }
            if (in_array($promotion->scope, ['product', 'both'])) {
                $promotion->products()->sync($data['product_ids'] ?? []);
            } else {
                $promotion->products()->sync([]);
            }
        } else {
            $promotion->courses()->sync([]);
            $promotion->products()->sync([]);
        }

        return redirect()->route('promotions.index')->with('success', 'แก้ไขโปรโมชัน/คูปองเรียบร้อยแล้ว');
    }

    // PATCH /promotions/{promotion}/toggle-status
    public function toggleStatus(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);

        return back()->with('success', $promotion->is_active ? 'เปิดใช้งานแล้ว' : 'ปิดใช้งานแล้ว');
    }

    // DELETE /promotions/{promotion}
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('success', 'ลบโปรโมชัน/คูปองเรียบร้อยแล้ว');
    }
}
