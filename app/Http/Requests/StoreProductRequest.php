<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'category_id'=>'required|exists:categories,id',
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'price'=>'required|decimal:0,1000000',
            'quantity'=>'required|integer',
            'images'=>'required|array',
            'images.*'=>'image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}
