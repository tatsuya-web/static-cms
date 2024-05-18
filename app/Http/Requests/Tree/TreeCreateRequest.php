<?php

namespace App\Http\Requests\Tree;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TreeType;
use App\Models\Tree;

use function PHPUnit\Framework\returnSelf;

class TreeCreateRequest extends FormRequest
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
            'name' => ['required_if:type,' . TreeType::Folder->value, 'nullable', 'string', 'max:255'],
            'type' => ['required', new Enum(TreeType::class)],
            // txtファイルもアップロードできるようにする
            'file' => ['required_if:type,' . TreeType::File->value, 'nullable', 'file', 'mimes:txt,html,xml,jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar', 'max:2048'],
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
            'name' => 'ファイル/フォルダ名',
            'type' => 'コンテンツ種別',
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

        /**
     * Get the validated data from the request.
     *
     * @return array<string, mixed>
     */
    public function validatedData(): array
    {
        return [
            'name' => $this->input('name'),
            'type' => $this->input('type'),
            'file' => $this->file('file'),
        ];
    }
}
