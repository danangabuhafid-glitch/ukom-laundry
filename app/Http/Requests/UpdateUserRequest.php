<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');
        if (is_object($id)) {
            $id = $id->id;
        }
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:user,email,'.$id],
            'password' => ['nullable', 'string', 'min:6'],
            'id_level' => ['required', 'exists:level,id'],
        ];
    }
}
