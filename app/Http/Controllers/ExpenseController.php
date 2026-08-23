<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // GET /expenses
    public function index(Request $request)
    {
        $expenses = Expense::query()
            ->category($request->get('category'))
            ->when($request->filled('date_from'), fn ($q) => $q->where('expense_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->where('expense_date', '<=', $request->date_to))
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('expenses.index', compact('expenses'));
    }

    // GET /expenses/create
    public function create()
    {
        return view('expenses.create');
    }

    // POST /expenses
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['recorded_by'] = auth()->user()->name ?? 'แอดมิน';

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'บันทึกรายจ่ายเรียบร้อยแล้ว');
    }

    // GET /expenses/{expense}/edit
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    // PUT /expenses/{expense}
    public function update(Request $request, Expense $expense)
    {
        $data = $this->validated($request);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'แก้ไขรายจ่ายเรียบร้อยแล้ว');
    }

    // DELETE /expenses/{expense}
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'ลบรายจ่ายเรียบร้อยแล้ว');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category'     => ['required', 'in:course,product_cost,rent,staff,maintenance,other'],
            'expense_date' => ['required', 'date'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'title'        => ['required', 'string', 'max:150'],
            'note'         => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
