<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'        => $this->name ? trim(strip_tags($this->name)) : null,
            'description' => $this->description ? trim(strip_tags($this->description)) : null,
        ]);
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name'           => ['required', 'string', 'max:200'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'category_id'    => ['nullable', 'exists:product_categories,id'],
            'price'          => ['required', 'numeric', 'min:0'],
            'cost_price'     => ['nullable', 'numeric', 'min:0'],
            'reorder_level'  => ['required', 'integer', 'min:0'],
            'status'         => ['required', 'in:active,inactive'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'initial_stock'  => ['nullable', 'integer', 'min:0'], // ใช้เฉพาะตอนสร้างสินค้าใหม่
        ];
    }
}