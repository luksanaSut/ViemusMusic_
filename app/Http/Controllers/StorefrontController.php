<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockMovement;
use App\Models\StoreSale;
use App\Models\StoreSaleItem;
use App\Models\StudentCreditTransaction;
use App\Services\LoyaltyService;
use App\Services\PromotionEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorefrontController extends Controller
{
    private function myStudents(Request $request)
    {
        $user = $request->user();

        if ($user->isStudent() && $user->student) {
            return collect([$user->student]);
        }
        if ($user->isGuardian() && $user->guardian) {
            return $user->guardian->students;
        }

        return collect();
    }

    // GET /store — หน้าร้านค้า เลือกสินค้าใส่ตะกร้า
    public function index(Request $request)
    {
        $students = $this->myStudents($request);
        if ($students->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'บัญชีนี้ยังไม่ได้ผูกกับข้อมูลนักเรียน ไม่สามารถสั่งซื้อสินค้าได้');
        }

        $products = Product::where('status', 'active')->where('stock_quantity', '>', 0)
            ->search($request->get('q'))
            ->category($request->get('category_id'))
            ->with('category')
            ->orderBy('name')
            ->get();

        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();

        return view('storefront.index', compact('products', 'categories', 'students'));
    }

    // POST /store/checkout — สร้างคำสั่งซื้อ (ยังไม่ตัดสต็อก รอชำระเงินก่อน)
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'student_id'          => ['required', 'exists:students,id'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'exists:products,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
        ]);

        $this->authorizeStudent($request, $data['student_id']);

        // เช็คสต็อกคร่าวๆ ก่อน (เช็คจริงอีกครั้งตอนยืนยันจ่ายเงิน กันของหมดระหว่างรอ)
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return back()->withInput()->with('error', "สินค้า \"{$product->name}\" คงเหลือแค่ {$product->stock_quantity} ชิ้น ไม่พอสั่งซื้อ {$item['quantity']} ชิ้น");
            }
        }

        $total = 0;
        $itemsData = [];
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;
            $itemsData[] = ['product' => $product, 'quantity' => $item['quantity'], 'subtotal' => $subtotal];
        }

        $productIds = collect($data['items'])->pluck('product_id')->all();
        // ตอนสร้างคำสั่งซื้อ ใช้เฉพาะโปรโมชันอัตโนมัติก่อน (ยังไม่มีการกรอกโค้ดคูปอง)
        $promo = app(PromotionEngine::class)->applyToCart('product', $productIds, (float) $total, null, $data['student_id'], null);

        $sale = null;
        DB::transaction(function () use ($request, $data, $total, $itemsData, $promo, &$sale) {
            $sale = StoreSale::create([
                'sale_no'              => 'STK-' . now()->format('Ymd') . '-' . str_pad(StoreSale::whereDate('created_at', now())->count() + 1, 4, '0', STR_PAD_LEFT),
                'student_id'           => $data['student_id'],
                'auto_promotion_id'    => $promo['auto_promotion']?->id,
                'total_amount'         => $total,
                'auto_discount_amount' => $promo['auto_discount'],
                'net_payable'          => $promo['net_payable'],
                'status'               => 'pending_payment',
                'ordered_by_user_id'   => $request->user()->id,
            ]);

            foreach ($itemsData as $d) {
                StoreSaleItem::create([
                    'store_sale_id' => $sale->id,
                    'product_id'    => $d['product']->id,
                    'product_name'  => $d['product']->name,
                    'quantity'      => $d['quantity'],
                    'unit_price'    => $d['product']->price,
                    'subtotal'      => $d['subtotal'],
                ]);
            }
        });

        return redirect()->route('store.show', $sale)->with('success', 'สร้างคำสั่งซื้อเรียบร้อยแล้ว กรุณาชำระเงินเพื่อยืนยันคำสั่งซื้อ');
    }

    // GET /store/orders/{storeSale}/edit — หน้าแก้ไขคำสั่งซื้อ (เฉพาะที่ยังไม่จ่ายเงิน)
    public function edit(Request $request, StoreSale $storeSale)
    {
        $this->authorizeOrderOwner($request, $storeSale);

        if ($storeSale->status !== 'pending_payment') {
            return redirect()->route('store.show', $storeSale)->with('error', 'คำสั่งซื้อนี้ไม่สามารถแก้ไขได้แล้ว');
        }

        $storeSale->load('items.product');

        $products = Product::where('status', 'active')
            ->search($request->get('q'))
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('storefront.edit', compact('storeSale', 'products'));
    }

    // PUT /store/orders/{storeSale} — บันทึกการแก้ไขรายการสินค้าในคำสั่งซื้อ
    public function update(Request $request, StoreSale $storeSale)
    {
        $this->authorizeOrderOwner($request, $storeSale);

        if ($storeSale->status !== 'pending_payment') {
            return back()->with('error', 'คำสั่งซื้อนี้ไม่สามารถแก้ไขได้แล้ว');
        }

        $data = $request->validate([
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'exists:products,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'ตะกร้าต้องมีสินค้าอย่างน้อย 1 รายการ หากต้องการยกเลิกทั้งหมดให้กดปุ่มยกเลิกคำสั่งซื้อแทน',
        ]);

        // เช็คสต็อกคงเหลือจริง ณ ตอนนี้ (สินค้าอาจถูกคนอื่นซื้อไปตั้งแต่ตอนสร้างคำสั่งซื้อ)
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return back()->withInput()->with('error', "สินค้า \"{$product->name}\" คงเหลือแค่ {$product->stock_quantity} ชิ้น ไม่พอกับจำนวนที่เลือก");
            }
        }

        $warning = null;

        DB::transaction(function () use ($data, $storeSale, &$warning) {
            $storeSale->items()->delete();

            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                StoreSaleItem::create([
                    'store_sale_id' => $storeSale->id,
                    'product_id'    => $product->id,
                    'product_name'  => $product->name,
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $product->price,
                    'subtotal'      => $subtotal,
                ]);
            }

            // ตะกร้าเปลี่ยนไป ต้องคำนวณโปรโมชัน/คูปองใหม่ทั้งหมด เพราะเงื่อนไข (เช่น ซื้อครบ X, สินค้าที่เข้าเงื่อนไข) อาจไม่เข้าแล้ว
            $productIds = collect($data['items'])->pluck('product_id')->all();
            $promo = app(PromotionEngine::class)->applyToCart('product', $productIds, (float) $total, $storeSale->promotion_code, $storeSale->student_id, null);

            if ($promo['error']) {
                // โค้ดคูปองเดิมใช้ไม่ได้กับตะกร้าใหม่แล้ว ตัดออกแทนที่จะฟ้อง error การแก้ไขตะกร้า
                $warning = 'โค้ดส่วนลดที่ใช้อยู่ไม่สามารถใช้ได้กับตะกร้าที่แก้ไข จึงถูกยกเลิกโดยอัตโนมัติ';
                $promo = app(PromotionEngine::class)->applyToCart('product', $productIds, (float) $total, null, $storeSale->student_id, null);
            }

            // ตะกร้าเปลี่ยน ยอดเดิมที่เคยใช้แต้ม/เครดิตคำนวณไว้ไม่ตรงกับยอดใหม่แล้ว ต้องให้เลือกใหม่ (ป้องกันยอดสุทธิเพี้ยน)
            $storeSale->update([
                'total_amount'           => $total,
                'auto_promotion_id'      => $promo['auto_promotion']?->id,
                'auto_discount_amount'   => $promo['auto_discount'],
                'promotion_id'           => $promo['coupon']?->id,
                'promotion_code'         => $promo['coupon']?->code,
                'discount_amount'        => $promo['coupon_discount'],
                'points_used'            => 0,
                'points_discount_amount' => 0,
                'credit_used'            => 0,
                'net_payable'            => $promo['net_payable'],
            ]);
        });

        return redirect()->route('store.show', $storeSale)
            ->with('success', 'แก้ไขรายการสั่งซื้อเรียบร้อยแล้ว')
            ->when($warning, fn ($redirect) => $redirect->with('warning', $warning));
    }

    // POST /store/orders/{storeSale}/apply-discount — กรอกโค้ดคูปองที่หน้าคำสั่งซื้อ (รอชำระเงิน)
    public function applyDiscount(Request $request, StoreSale $storeSale)
    {
        $this->authorizeOrderOwner($request, $storeSale);

        if ($storeSale->status !== 'pending_payment') {
            return back()->with('error', 'คำสั่งซื้อนี้ไม่สามารถแก้ไขส่วนลดได้แล้ว');
        }

        $data = $request->validate([
            'coupon_code' => ['nullable', 'string', 'max:30'],
            'use_points'  => ['nullable', 'boolean'],
            'use_credit'  => ['nullable', 'boolean'],
        ]);

        $storeSale->load('items');
        $productIds = $storeSale->items->pluck('product_id')->all();

        $promo = app(PromotionEngine::class)->applyToCart(
            'product',
            $productIds,
            (float) $storeSale->total_amount,
            $data['coupon_code'] ?? null,
            $storeSale->student_id,
            null
        );

        if ($promo['error']) {
            return back()->with('error', $promo['error']);
        }

        $student = $storeSale->student;
        $running = $promo['net_payable'];

        $pointsDiscount = 0;
        $pointsUsed = 0;
        if ($request->boolean('use_points')) {
            $pointsDiscount = $student->maxPointsRedeemableValue($running);
            $pointsUsed = (int) ($pointsDiscount * 10);
            $running -= $pointsDiscount;
        }

        $creditUsed = 0;
        if ($request->boolean('use_credit')) {
            $creditUsed = min($student->creditBalance(), $running);
            $running -= $creditUsed;
        }

        $storeSale->update([
            'promotion_id'           => $promo['coupon']?->id,
            'promotion_code'         => $promo['coupon']?->code,
            'discount_amount'        => $promo['coupon_discount'],
            'points_used'            => $pointsUsed,
            'points_discount_amount' => $pointsDiscount,
            'credit_used'            => $creditUsed,
            'net_payable'            => max(0, round($running, 2)),
        ]);

        return back()->with('success', 'อัปเดตส่วนลดเรียบร้อยแล้ว');
    }

    // PATCH /store/orders/{storeSale}/cancel — ยกเลิกคำสั่งซื้อเอง (เฉพาะที่ยังไม่จ่ายเงิน)
    public function cancelByCustomer(Request $request, StoreSale $storeSale)
    {
        $this->authorizeOrderOwner($request, $storeSale);

        if ($storeSale->status !== 'pending_payment') {
            return back()->with('error', 'คำสั่งซื้อนี้ไม่สามารถยกเลิกได้แล้ว');
        }

        $storeSale->update(['status' => 'cancelled']);

        return redirect()->route('store.my-orders')->with('success', 'ยกเลิกคำสั่งซื้อเรียบร้อยแล้ว');
    }

    // GET /store/orders/{storeSale} — หน้าสรุป/ชำระเงิน
    public function show(Request $request, StoreSale $storeSale)
    {
        $this->authorizeOrderOwner($request, $storeSale);
        $storeSale->load('items', 'student');

        return view('storefront.show', compact('storeSale'));
    }

    // POST /store/orders/{storeSale}/confirm-payment — ยืนยันชำระเงิน + ตัดสต็อกจริง
    public function confirmPayment(Request $request, StoreSale $storeSale)
    {
        $this->authorizeOrderOwner($request, $storeSale);

        if ($storeSale->status !== 'pending_payment') {
            return back()->with('error', 'คำสั่งซื้อนี้ถูกดำเนินการไปแล้ว');
        }

        $data = $request->validate([
            'payment_method'    => ['required', 'in:promptpay,transfer,credit_card'],
            'payment_reference' => ['required_if:payment_method,credit_card', 'nullable', 'string', 'max:100'],
            'payment_proof'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'delivery_method'          => ['required', 'in:pickup,delivery'],
            'delivery_recipient_name'  => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:150'],
            'delivery_phone'           => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:20'],
            'delivery_address'         => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:1000'],
        ], [
            'payment_reference.required_if'       => 'กรุณากรอกเลขอ้างอิงการทำรายการบัตร',
            'delivery_recipient_name.required_if' => 'กรุณากรอกชื่อผู้รับ',
            'delivery_phone.required_if'          => 'กรุณากรอกเบอร์โทรติดต่อ',
            'delivery_address.required_if'        => 'กรุณากรอกที่อยู่จัดส่ง',
        ]);

        $storeSale->load('items.product');

        // ===== ตรวจสต็อกอีกครั้ง ณ เวลาจ่ายจริง กันของหมดระหว่างรอ =====
        foreach ($storeSale->items as $item) {
            if (!$item->product || $item->product->stock_quantity < $item->quantity) {
                return back()->with('error', "สินค้า \"{$item->product_name}\" มีไม่พอแล้ว กรุณาติดต่อโรงเรียนเพื่อยกเลิก/ปรับคำสั่งซื้อ");
            }
        }

        DB::transaction(function () use ($request, $data, $storeSale) {
            if ($request->hasFile('payment_proof')) {
                $storeSale->payment_proof_path = $request->file('payment_proof')->store('store-payment-proofs', 'public');
            }
            $storeSale->payment_method = $data['payment_method'];
            $storeSale->payment_reference = $data['payment_reference'] ?? null;
            $storeSale->confirmed_at = now();
            $storeSale->status = 'completed';

            $storeSale->delivery_method = $data['delivery_method'];
            if ($data['delivery_method'] === 'delivery') {
                $storeSale->delivery_recipient_name = $data['delivery_recipient_name'];
                $storeSale->delivery_phone = $data['delivery_phone'];
                $storeSale->delivery_address = $data['delivery_address'];
                $storeSale->delivery_status = 'preparing';
            } else {
                $storeSale->delivery_status = 'ready_for_pickup';
            }

            $storeSale->save();

            app(PromotionEngine::class)->recordUsage([
                'auto_promotion'  => $storeSale->autoPromotion,
                'auto_discount'   => $storeSale->auto_discount_amount,
                'coupon'          => $storeSale->promotion,
                'coupon_discount' => $storeSale->discount_amount,
            ], [
                'store_sale_id' => $storeSale->id,
                'student_id'    => $storeSale->student_id,
            ]);

            $student = $storeSale->student;
            $loyalty = app(LoyaltyService::class);

            if ($storeSale->points_used > 0) {
                $loyalty->redeemPoints($student, $storeSale->points_used, sale: $storeSale, reason: 'แลกแต้มเป็นส่วนลดคำสั่งซื้อ ' . $storeSale->sale_no);
            }

            if ($storeSale->credit_used > 0) {
                $newBalance = $student->creditBalance() - $storeSale->credit_used;
                StudentCreditTransaction::create([
                    'student_id'    => $student->id,
                    'type'          => 'use',
                    'amount'        => -$storeSale->credit_used,
                    'balance_after' => $newBalance,
                    'reason'        => 'ใช้ชำระค่าสินค้า ' . $storeSale->sale_no,
                ]);
            }

            $earned = (int) floor(($storeSale->net_payable ?? $storeSale->total_amount) / 100);
            if ($earned > 0) {
                $loyalty->earnPoints($student, $earned, sale: $storeSale, reason: 'สะสมแต้มจากการซื้อสินค้า ' . $storeSale->sale_no);
            }

            // ===== Business Rule: ตัดสต็อกอัตโนมัติ (เกิดขึ้นตอนยืนยันจ่ายเงินสำเร็จเท่านั้น) =====
            foreach ($storeSale->items as $item) {
                $product = $item->product;
                $product->decrement('stock_quantity', $item->quantity);
                $product->refresh();

                StockMovement::create([
                    'product_id'    => $product->id,
                    'type'          => 'out',
                    'quantity'      => -$item->quantity,
                    'balance_after' => $product->stock_quantity,
                    'reason'        => 'ขายสินค้า (สั่งซื้อออนไลน์ ' . $storeSale->sale_no . ')',
                    'store_sale_id' => $storeSale->id,
                    'created_by'    => $storeSale->orderedBy?->displayName() ?? 'ลูกค้า',
                ]);
            }
        });

        AppNotification::notifyAdmins(
            'มีคำสั่งซื้อสินค้าใหม่',
            "{$storeSale->student->full_name} สั่งซื้อสินค้า {$storeSale->items->count()} รายการ ยอดรวม ฿" . number_format($storeSale->net_payable ?? $storeSale->total_amount, 2) . ' (' . $storeSale->deliveryMethodLabel() . ')',
            route('store-sales.show', $storeSale)
        );
        return back()->with('success', 'ชำระเงินเรียบร้อยแล้ว คำสั่งซื้อสำเร็จ');
    }

    // GET /my-orders — ประวัติคำสั่งซื้อของตัวเอง/บุตรหลาน
    public function myOrders(Request $request)
    {
        $students = $this->myStudents($request);
        $studentIds = $students->pluck('id');

        $status = $request->get('status');

        $baseQuery = StoreSale::whereIn('student_id', $studentIds);

        $counts = [
            'pending_payment' => (clone $baseQuery)->where('status', 'pending_payment')->count(),
            'completed'        => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled'        => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        $orders = StoreSale::with('items')
            ->whereIn('student_id', $studentIds)
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('storefront.my-orders', compact('orders', 'counts', 'status'));
    }

    private function authorizeStudent(Request $request, int $studentId): void
    {
        $user = $request->user();
        if ($user->isStudent() && $user->student_id === $studentId) return;
        if ($user->isGuardian() && $user->guardian?->students->pluck('id')->contains($studentId)) return;
        abort(403, 'คุณสามารถสั่งซื้อให้ตัวเองหรือบุตรหลานที่ผูกกับบัญชีของคุณเท่านั้น');
    }

    private function authorizeOrderOwner(Request $request, StoreSale $storeSale): void
    {
        if ($request->user()->isAdmin()) return;
        $this->authorizeStudent($request, $storeSale->student_id);
    }
}