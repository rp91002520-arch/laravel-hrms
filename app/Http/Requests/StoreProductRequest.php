<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
{
    return [
        'name' => 'required|unique:products,name,NULL,id,user_id,' . auth()->id(),
        'price'=>'required|numeric',
        'stock'=>'required|integer',
        'description'=>'nullable',
        'image'=>'nullable|image|mimes:jpg,png,jpeg|max:2048'
    ];
}
}
