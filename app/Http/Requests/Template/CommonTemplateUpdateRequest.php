<?php

namespace App\Http\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;

class CommonTemplateUpdateRequest extends FormRequest
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
            'description' => ['nullable', 'string', 'max:255'],
            'format' => ['nullable', 'file', 'mimes:json'],
            'src' => ['nullable', 'file', 'mimes:html,txt'],
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
            'description.string' => '説明は文字列で入力してください。',
            'description.max' => '説明は255文字以内で入力してください。',
            'format.file' => 'フォーマットはファイルで入力してください。',
            'format.mimes' => 'フォーマットはjson形式で入力してください。',
            'src.file' => 'ソースコードはファイルで入力してください。',
            'src.mimes' => 'ソースコードはhtml形式で入力してください。',
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
            'description' => '説明',
            'format' => 'フォーマット',
            'src' => 'ソースコード',
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
            'description' => $this->input('description') ?? '',
            'format' => $this->file('format') ?? null,
            'src' => $this->file('src') ?? null,
        ];
    }

    /*
    * srcは拡張子が.htmlのファイルのみ許可する
    * 
    */
}
