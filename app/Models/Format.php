<?php

namespace App\Models;

use App\Enums\TemplateFormat;

class Format
{
    private string $label;

    private string $name;

    private TemplateFormat $type;

    private bool $required;

    private string $placeholder;

    private array $options;

    private ?int $min = null;

    private ?int $max = null;

    private string $accept;

    public function __construct(object $data){
        if(TemplateFormat::from($data->type) === null) {
            throw new \InvalidArgumentException('invalid_type');
        }

        $this->label = $data->label;
        $this->name = $data->name;
        $this->type = TemplateFormat::from($data->type);
        $this->required = $data->required;
        $this->placeholder = $data->placeholder ?? '';
        $this->options = $data->options ?? [];
        $this->min = $data->min ?? null;
        $this->max = $data->max ?? null;
        $this->accept = $data->accept ?? '';
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function getAccept(): string
    {
        return TemplateFormat::getAcceptType($this->accept);
    }

    public function getAcceptString(): string
    {
        return TemplateFormat::getAcceptTypeString($this->accept);
    }

    public function hasOptions(): bool
    {
        return $this->type->hasOptions();
    }

    public function hasPlaceholder(): bool
    {
        return $this->type->hasPlaceholder();
    }

    public function hasAccept(): bool
    {
        return $this->type->hasAccept();
    }

    public function hasMin(): bool
    {
        return $this->type->hasMin();
    }

    public function hasMax(): bool
    {
        return $this->type->hasMax();
    }
}