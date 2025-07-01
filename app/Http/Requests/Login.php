<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class Login extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean', // Asegura que el campo remember sea booleano
        ];
        
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'password.required' => 'El campo de contraseña es obligatorio.',
            'remember.boolean' => 'El campo recordar debe ser verdadero o falso.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Asegurarse de que el campo 'remember' sea un booleano
        $this->merge([
            'email' => strtolower(trim($this->email)),
        ]);
    }
}
