<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StoreSale;
use App\Models\StoreSaleItem;
use App\Models\Student;
use App\Services\PromotionEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreSaleController extends Controller
{
    // GET /store-sales — ประวัติการขายสินค้าทั้งหมด
    public function index(Request $request)
    {
        $sales = StoreSale::with(['items', 'student', 'orderedBy'])
            ->when($request->filled('q'), fn($q) => $q->where('sale_no', 'like', '%' . $request->q . '%')
                ->orWhere('buyer_name', 'like', '%' . $request->q . '%'))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('store-sales.index', compact('sales'));
    }

    // GET /store-sales/create — หน้าขายสินค้าแบบเร็ว (คล้าย POS)
    public function create()
    {
        $products = Product::where('status', 'active')->where('stock_quantity', '>', 0)
            ->with('category')->orderBy('name')->get();
        $students = Student::where('status', '!=', 'cancelled')->orderBy('full_name')->get(['id', 'full_name', 'student_code']);

        return view('store-sales.create', compact('products', 'students'));
    }

    // POST /store-sales — Business Rule: ตัดสต็อกอัตโนมัติเมื่อขายสำเร็จ
    public function store(Request $request)
    {
        $data = $request->validate([
            'buyer_name'      => ['nullable', 'string', 'max:150'],
            'student_id'      => ['nullable', 'exists:students,id'],
            'payment_method'  => ['required', 'in:cash,transfer,credit_card,promptpay'],
            'coupon_code'     => ['nullable', 'string', 'max:30'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
        ]);

        // ตรวจสอบสต็อกพอไหมก่อนทำรายการทั้งหมด
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return back()->withInput()->with('error', "สินค้า \"{$product->name}\" คงเหลือแค่ {$product->stock_quantity} ชิ้น ไม่พอขาย {$item['quantity']} ชิ้น");
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
        $promo = app(PromotionEngine::class)->applyToCart(
            'product',
            $productIds,
            (float) $total,
            $data['coupon_code'] ?? null,
            $data['student_id'] ?? null,
            $data['buyer_name'] ?? null
        );

        if ($promo['error']) {
            return back()->withInput()->with('error', $promo['error']);
        }

        $sale = null;
        DB::transaction(function () use ($data, $promo, $total, $itemsData, &$sale) {
            $sale = StoreSale::create([
                'sale_no'              => 'STK-' . now()->format('Ymd') . '-' . str_pad(StoreSale::whereDate('created_at', now())->count() + 1, 4, '0', STR_PAD_LEFT),
                'buyer_name'           => $data['buyer_name'] ?? null,
                'student_id'           => $data['student_id'] ?? null,
                'promotion_id'         => $promo['coupon']?->id,
                'promotion_code'       => $promo['coupon']?->code,
                'auto_promotion_id'    => $promo['auto_promotion']?->id,
                'total_amount'         => $total,
                'discount_amount'      => $promo['coupon_discount'],
                'auto_discount_amount' => $promo['auto_discount'],
                'net_payable'          => $promo['net_payable'],
                'payment_method'       => $data['payment_method'],
                'status'               => 'completed',
                'sold_by'              => auth()->user()->name ?? 'แอดมิน',
            ]);

            app(PromotionEngine::class)->recordUsage($promo, [
                'store_sale_id'    => $sale->id,
                'student_id'       => $data['student_id'] ?? null,
                'buyer_identifier' => $data['buyer_name'] ?? null,
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

                // ===== Business Rule: ตัดสต็อกอัตโนมัติ =====
                $d['product']->decrement('stock_quantity', $d['quantity']);
                $d['product']->refresh();

                StockMovement::create([
                    'product_id'    => $d['product']->id,
                    'type'          => 'out',
                    'quantity'      => -$d['quantity'],
                    'balance_after' => $d['product']->stock_quantity,
                    'reason'        => 'ขายสินค้า (' . $sale->sale_no . ')',
                    'store_sale_id' => $sale->id,
                    'created_by'    => auth()->user()->name ?? 'แอดมิน',
                ]);
            }
        });

        return redirect()->route('store-sales.show', $sale)->with('success', 'บันทึกการขายเรียบร้อยแล้ว ตัดสต็อกอัตโนมัติแล้ว');
    }

    // GET /store-sales/{storeSale}
    public function show(StoreSale $storeSale)
    {
        $storeSale->load(['items.product', 'student']);
        return view('store-sales.show', compact('storeSale'));
    }

    // PATCH /store-sales/{storeSale}/cancel — ยกเลิกการขาย คืนสต็อกกลับอัตโนมัติ
    public function cancel(StoreSale $storeSale)
    {
        if ($storeSale->status !== 'completed') {
            return back()->with('error', 'รายการนี้ถูกยกเลิกไปแล้ว');
        }

        DB::transaction(function () use ($storeSale) {
            foreach ($storeSale->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                    $product->refresh();

                    StockMovement::create([
                        'product_id'    => $product->id,
                        'type'          => 'in',
                        'quantity'      => $item->quantity,
                        'balance_after' => $product->stock_quantity,
                        'reason'        => 'ยกเลิกการขาย คืนสต็อก (' . $storeSale->sale_no . ')',
                        'store_sale_id' => $storeSale->id,
                        'created_by'    => auth()->user()->name ?? 'แอดมิน',
                    ]);
                }
            }

            app(PromotionEngine::class)->voidUsage(['store_sale_id' => $storeSale->id]);

            $storeSale->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'ยกเลิกการขายเรียบร้อยแล้ว คืนสต็อกสินค้าให้อัตโนมัติแล้ว');
    }

    // PATCH /store-sales/{storeSale}/delivery-status — Admin อัปเดตสถานะจัดส่ง/เลขพัสดุ
    public function updateDeliveryStatus(Request $request, StoreSale $storeSale)
    {
        $data = $request->validate([
            'delivery_status'      => ['required', 'in:preparing,shipped,ready_for_pickup,picked_up'],
            'delivery_tracking_no' => ['nullable', 'string', 'max:100'],
        ]);

        $storeSale->update($data);

        return back()->with('success', 'อัปเดตสถานะการจัดส่งเรียบร้อยแล้ว');
    }
}