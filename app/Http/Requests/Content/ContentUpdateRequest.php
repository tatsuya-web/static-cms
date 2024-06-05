<?php

namespace App\Http\Requests\Content;

use App\Models\Template;
use App\Rules\TemplateFormatRule;
use Illuminate\Foundation\Http\FormRequest;

class ContentUpdateRequest extends FormRequest
{
    private Template $template;

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
        $rules = [];

        $this->template = Template::findOrFail($this->template_id);

        foreach($this->template->format_items as $format_item) {
            $rules[$format_item->getValidationName()] = [
                new TemplateFormatRule($format_item),
            ];
        }

        return $rules;
    }

    public function validatedData(): array
    {
        $data = [];

        foreach($this->template->format_items as $format_item) {
            // fileの場合は、ファイルを保存してパスを保存する
            if($format_item->getType() === 'file' && $this->file($format_item->getValidationName())) {
                $data[$format_item->getName()] = $this->file($format_item->getValidationName())->store('public');
                continue;
            // checkboxの場合は、チェックされている値を配列で保存する
            } else if($format_item->getType() === 'checkbox') {
                $options = $format_item->getOptions();
                foreach($options as $key => $option) {
                    $data[$format_item->getName()][$key] = $this->input($format_item->getValidationName() . '.' . $key);
                }
            } else {
                $data[$format_item->getName()] = $this->input($format_item->getValidationName());
            }
        }

        return $data;
    }
}
