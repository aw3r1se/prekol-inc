<?php

namespace App\Http\Requests\API\Product;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
//    public function rules(): array
//    {
//        return array_merge(
//            parent::rules(), [
//                //'uuid' => ['required', 'string', 'exists:products,uuid'],
//            ],
//        );
//    }
}
