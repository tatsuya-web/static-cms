<?php

namespace App\Http\Requests\Content;

use App\Enums\TemplateFormat;
use App\Enums\TemplateType;
use App\Models\Template;
use App\Rules\TemplateFormatRule;
use Illuminate\Foundation\Http\FormRequest;

class ContentCreateRequest extends FormRequest
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
            if($format_item->getType() === TemplateFormat::Group->value) {
                foreach($format_item->getItems() as $item) {
                    $rules[$item->getValidationName()] = [
                        new TemplateFormatRule($item),
                    ];
                }
                continue;
            }
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
            if($format_item->getType() === TemplateFormat::File->value && $this->file($format_item->getValidationName())) {
                $data[$format_item->getName()] = $this->file($format_item->getValidationName())->store('public');
                continue;
            // checkboxの場合は、チェックされている値を配列で保存する
            } else if($format_item->getType() === TemplateFormat::Checkbox->value) {
                $options = $format_item->getOptions();
                foreach($options as $key => $option) {
                    $data[$format_item->getName()][$key] = $this->input($format_item->getValidationName() . '.' . $key);
                }
            } else if($format_item->getType() === TemplateFormat::Group->value) {
                $data[$format_item->getName()] = [];
                foreach($format_item->getItems() as $item) {
                    $data[$format_item->getName()][$item->getName()] = $this->input($item->getValidationName());
                }
            } else {
                $data[$format_item->getName()] = $this->input($format_item->getValidationName());
            }
        }

        return $data;
    }
}
