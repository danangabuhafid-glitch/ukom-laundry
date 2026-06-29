<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'id_service' => ['required', 'exists:type_of_service,id'],
            'qty' => ['required', 'numeric', 'min:0.1'],
            'new_customer_name' => ['nullable', 'string', 'max:50'],
            'new_customer_phone' => ['nullable', 'string', 'max:15'],
            'new_customer_address' => ['nullable', 'string'],
            'order_pay' => ['required', 'numeric', 'min:0'],
        ];

        if ($this->input('id_customer') === 'new') {
            $rules['id_customer'] = ['required'];
            $rules['new_customer_name'] = ['required', 'string', 'max:50'];
            $rules['new_customer_phone'] = ['required', 'string', 'max:15'];
            $rules['new_customer_address'] = ['required', 'string'];
        } else {
            $rules['id_customer'] = ['required', 'exists:customer,id'];
        }

        return $rules;
    }
}
