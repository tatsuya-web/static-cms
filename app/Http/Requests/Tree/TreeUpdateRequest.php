<?php

namespace App\Http\Requests\Tree;

use Illuminate\Foundation\Http\FormRequest;

class TreeUpdateRequest extends FormRequest
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
            // txtファイルもアップロードできるようにする
            'file' => ['required', 'file', 'mimes:txt,html,xml,jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar', 'max:2048'],
        ];
    }

    /*
    * Get the validation attributes that apply to the request.
    *
    * @return array<string, string>
    */
    public function attributes(): array
    {
        return [
            'file' => 'ファイル',
        ];
    }

    /*
    * Get the validation messages that apply to the request.
    *
    * @return array<string, string>
    */
    public function messages(): array
    {
        return [
            'file.required_if' => ':attributeを選択してください。',
            'file.file' => ':attributeはファイルを選択してください。',
            'file.mimes' => ':attributeはhtml, xml, txt, jpeg, png, jpg, gif, svg, pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip, rarのいずれかのファイルを選択してください。',
            'file.max' => ':attributeは2MB以下のファイルを選択してください。',
        ];
    }
}