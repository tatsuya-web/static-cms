<?php

namespace App\Http\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;

class PageTemplateCreateRequest extends FormRequest
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
            'show_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'single_value_name' => ['required', 'string', 'max:255', 'unique:templates,single_value_name'],
            'multi_value_name' => ['required', 'string', 'max:255', 'unique:templates,multi_value_name'],
            'path' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'format' => ['required', 'file', 'mimes:json'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'show_name.required' => '表示名は必須です。',
            'show_name.string' => '表示名は文字列で入力してください。',
            'show_name.max' => '表示名は255文字以内で入力してください。',
            'name.required' => '名前は必須です。',
            'name.string' => '名前は文字列で入力してください。',
            'name.max' => '名前は255文字以内で入力してください。',
            'single_value_name.required' => '単一値名は必須です。',
            'single_value_name.string' => '単一値名は文字列で入力してください。',
            'single_value_name.max' => '単一値名は255文字以内で入力してください。',
            'multi_value_name.required' => '複数値名は必須です。',
            'multi_value_name.string' => '複数値名は文字列で入力してください。',
            'multi_value_name.max' => '複数値名は255文字以内で入力してください。',
            'description.string' => '説明は文字列で入力してください。',
            'description.max' => '説明は255文字以内で入力してください。',
            'format.required' => 'フォーマットは必須です。',
            'format.file' => 'フォーマットはファイルで入力してください。',
            'format.mimes' => 'フォーマットはjson形式で入力してください。',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'show_name' => '表示名',
            'name' => '名前',
            'single_value_name' => 'コンテンツ変数(単数)',
            'multi_value_name' => 'コンテンツ変数(複数形)',
            'path' => 'フォルダー名',
            'description' => '説明',
            'format' => '入力フォーマットファイル',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function validatedData(): array
    {
        return [
            'show_name' => $this->input('show_name'),
            'name' => $this->input('name'),
            'single_value_name' => $this->input('single_value_name'),
            'multi_value_name' => $this->input('multi_value_name'),
            'path' => $this->input('path') ?? '',
            'description' => $this->input('description') ?? '',
            'format' => $this->file('format'),
        ];
    }
}
