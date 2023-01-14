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
        $wareHouseIdRule = "nullable";
        $shopSelectionRule = "nullable";
        if (auth()->user()->role == "admin") {
            $wareHouseIdRule = "required";
            $shopSelectionRule = "required|array";
        }
        $advanceRules = []; 
        if (isset($this->distribution_required) && $this->distribution_required == 1) {
            $advanceRules = [
                'supplier_id' => "required",
                'warehouse_id' => $wareHouseIdRule,
                'purchase_quantity' => "required",
                'purchase_price' => "required",
                'shop_id' => $shopSelectionRule,
                'stock_quantity' => "required|lte:purchase_quantity",
                'ad_selling_price' => "required",
            ];
            $advanceRules["shop_id.*."] = "integer|exists:shops,id"; 
        }
        return array_merge($productRules, $advanceRules);
    }

    public function messages()
    {
        return [
            "supplier_id.required" => "Supplier is required for advance option",
            "warehouse_id.required" => "Warehouse is required for advance option",
            "shop_id.required" => "shop is required for advance option",
            "stock_quantity.lte" => "Distribute quantity must be less than or equal to purchase quantity",
        ];
    }

    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (auth()->user()->role == "admin" && gettype($this->shop_id) == "array") {
                if ((count($this->shop_id) * $this->stock_quantity) >  $this->purchase_quantity) {
                    $validator->errors()->add('Validation_error', 'Both shop quantity combinely bigger than purchase quantity');
                }
            }
          
        });
    }
}
