<?php

namespace App\Http\Controllers;

use App\Models\MembershipTier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MembershipTierController extends Controller
{
    // GET /membership-tiers
    public function index(Request $request)
    {
        $tiers = MembershipTier::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('membership-tiers.index', compact('tiers'));
    }

    // GET /membership-tiers/create
    public function create()
    {
        return view('membership-tiers.create');
    }

    // POST /membership-tiers
    public function store(Request $request)
    {
        $data = $this->validated($request);

        MembershipTier::create($data);

        return redirect()->route('membership-tiers.index')->with('success', 'เพิ่มระดับสมาชิกเรียบร้อยแล้ว');
    }

    // GET /membership-tiers/{membershipTier}/edit
    public function edit(MembershipTier $membershipTier)
    {
        return view('membership-tiers.edit', compact('membershipTier'));
    }

    // PUT /membership-tiers/{membershipTier}
    public function update(Request $request, MembershipTier $membershipTier)
    {
        $data = $this->validated($request);

        $membershipTier->update($data);

        return redirect()->route('membership-tiers.index')->with('success', 'แก้ไขระดับสมาชิกเรียบร้อยแล้ว');
    }

    // PATCH /membership-tiers/{membershipTier}/toggle-active
    public function toggleActive(MembershipTier $membershipTier)
    {
        $membershipTier->update(['is_active' => !$membershipTier->is_active]);

        return back()->with('success', $membershipTier->is_active ? 'เปิดใช้งานแล้ว' : 'ปิดใช้งานแล้ว');
    }

    // DELETE /membership-tiers/{membershipTier}
    public function destroy(MembershipTier $membershipTier)
    {
        $membershipTier->delete();

        return back()->with('success', 'ลบระดับสมาชิกเรียบร้อยแล้ว');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'sort_order'  => ['required', 'integer', 'min:0'],
            'min_spend'   => ['required', 'numeric', 'min:0'],
            'benefits'    => ['nullable', 'string', 'max:2000'],
            'badge_color' => ['required', Rule::in(['secondary', 'success', 'primary', 'warning', 'danger', 'dark', 'info'])],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
