<?php

namespace App\Models;

use App\Enums\TemplateFormat;
use Illuminate\Support\Collection;

class Format
{
    private string $label;

    private string $parent = '';

    private string $name;

    private TemplateFormat $type;

    private bool $required;

    private string $placeholder;

    private array $options;

    private ?int $min = null;

    private ?int $max = null;

    private string $accept;

    private bool $index = false;

    private Collection $items;

    public function __construct(object $data){
        $this->label = $data->label;
        $this->name = $data->name;
        $this->type = TemplateFormat::from($data->type);
        $this->required = $data->required ?? false;
        $this->placeholder = $data->placeholder ?? '';
        $this->options = $data->options ?? [];
        $this->min = $data->min ?? null;
        $this->max = $data->max ?? null;
        $this->accept = $data->accept ?? '';
        $this->index = $data->index ?? false;

        if($this->type->hasItems()){
            $this->items = collect($data->items)->map(function($item){
                if($this->type->isGroup()){
                    return (new Format($item))->setGroup($this->name);
                } else {
                    return new Format($item);
                }
            });
        } else {
            $this->items = collect([]);
        }
    }

    public function setGroup(string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): string
    {
        return $this->parent;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInputName(): string
    {
        if($this->parent !== ''){
            return $this->parent . '[' . $this->name . ']';
        } else {
            return $this->name;
        }
    }

    public function getValidationName(): string
    {
        if($this->parent !== ''){
            return $this->parent . '.' . $this->name;
        } else {
            return $this->name;
        }
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

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function isIndex(): bool
    {
        return $this->index;
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

    public function hasItems(): bool
    {
        return $this->type->hasItems();
    }

    public function hasParent(): bool
    {
        return $this->parent !== '';
    }

    public function whereItemName(string $name): ?Format
    {
        $item = $this->items->filter(function($item) use ($name){
            dd($item->getName(), $name); // dd() is not executed
            return $item->getName() === $name;
        })->first();

        return $item;
    }
}