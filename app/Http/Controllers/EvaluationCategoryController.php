<?php

namespace App\Http\Controllers;

use App\Models\EvaluationCategory;
use Illuminate\Http\Request;

class EvaluationCategoryController extends Controller
{
    public function index()
    {
        $categories = EvaluationCategory::orderBy('sort_order')->get();
        return view('evaluation-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);
        $data['sort_order'] = EvaluationCategory::max('sort_order') + 1;

        EvaluationCategory::create($data);

        return back()->with('success', 'เพิ่มหมวดหมู่ประเมินผลเรียบร้อยแล้ว');
    }

    public function toggleActive(EvaluationCategory $evaluationCategory)
    {
        $evaluationCategory->update(['is_active' => !$evaluationCategory->is_active]);
        return back()->with('success', 'อัปเดตสถานะแล้ว');
    }

    public function destroy(EvaluationCategory $evaluationCategory)
    {
        $evaluationCategory->delete();
        return back()->with('success', 'ลบหมวดหมู่แล้ว');
    }
}