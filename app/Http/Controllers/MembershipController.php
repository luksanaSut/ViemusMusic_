<?php

namespace App\Http\Controllers;

use App\Models\MembershipTier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    private function myStudents(Request $request): Collection
    {
        $user = $request->user();

        if ($user->isStudent() && $user->student) {
            return Collection::make([$user->student]);
        }
        if ($user->isGuardian() && $user->guardian) {
            return $user->guardian->students;
        }

        return Collection::make();
    }

    // GET /my-membership
    public function index(Request $request)
    {
        $students = $this->myStudents($request)->load('membership.tier');

        $tiers = MembershipTier::where('is_active', true)->orderBy('sort_order')->get();

        return view('membership.index', compact('students', 'tiers'));
    }

    // GET /my-points
    public function points(Request $request)
    {
        $students = $this->myStudents($request)->load(['pointTransactions' => fn ($q) => $q->orderByDesc('created_at')]);

        return view('membership.points', compact('students'));
    }
}
