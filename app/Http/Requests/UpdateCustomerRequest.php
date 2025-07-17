<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'reference' => 'sometimes|required|string|max:255',
            'customer_category_id' => 'sometimes|required|exists:customer_categories,id',
            'start_date' => 'sometimes|required|date',
            'description' => 'sometimes|required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The customer name is required.',
            'reference.required' => 'The reference is required.',
            'customer_category_id.required' => 'The category is required.',
            'customer_category_id.exists' => 'The selected category does not exist.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'description.required' => 'The description is required.',
        ];
    }
}
