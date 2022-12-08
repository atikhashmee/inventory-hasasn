<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class CreateProductRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $productRules = Product::$rules;
        $advanceRules = []; 
        if (isset($this->distribution_required) && $this->distribution_required == 1) {
            $advanceRules = [
                'supplier_id' => "required",
                'warehouse_id' => "required",
                'purchase_quantity' => "required",
                'purchase_price' => "required",
                'shop_id' => "required",
                'stock_quantity' => "required",
                'ad_selling_price' => "required",
            ];
        }
        return array_merge($productRules, $advanceRules);
    }

    public function messages()
    {
        return [
            "supplier_id.required" => "Supplier is required for advance option",
            "warehouse_id.required" => "Warehouse is required for advance option",
            "shop_id.required" => "shop is required for advance option",
        ];
    }
}
