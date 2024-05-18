<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Role;

class UserUpdateRequest extends FormRequest
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
        $data = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'role' => ['required' , new Enum(Role::class)],
        ];

        if ($this->filled('password')) {
            $data['password'] = ['string', 'min:8', 'confirmed'];
        }

        return $data;
    }

    /*
    * Get the validation attributes that apply to the request.
    *
    * @return array<string, string>
    */
    public function attributes(): array
    {
        return [
            'name' => '名前',
            'email' => 'メールアドレス',
            'role' => '権限',
            'password' => 'パスワード',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role.enum' => '権限を選択してください。',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @return array<string, mixed>
     */
    public function validatedData(): array
    {
        $data = [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'role' => $this->input('role'),
        ];

        if ($this->filled('password')) {
            $data['password'] = bcrypt($this->input('password'));
        }

        return $data;
    }
}
