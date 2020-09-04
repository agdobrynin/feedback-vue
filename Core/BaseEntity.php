<?php

declare(strict_types=1);

namespace Core;

abstract class BaseEntity
{
    /** @var string Дефолтное имя таблицы Имя класс + окончание "s" множественное число */
    protected $table;
    /** @var string первичный ключ таблицы */
    protected $primaryKey = 'id';
    /** @var mixed значение первичного ключа */
    public $id;

    /** @var \ReflectionProperty[] */
    private $properties;
    private $placeholders;
    private $values;
    protected $fields;

    public function __construct()
    {
        $refClass = new \ReflectionClass($this);
        $this->properties = array_filter($refClass->getProperties(\ReflectionProperty::IS_PUBLIC), function (\ReflectionProperty $property) {
            return $property->name !== $this->primaryKey;
        });
        $this->table = $refClass->getShortName() . 's';
    }

    public function getTable(): ?string
    {
        return $this->table;
    }

    public function getPrimaryKeyName(): string
    {
        return $this->primaryKey;
    }

    public function getPlaceholders(): string
    {
        if (!$this->placeholders) {
            $this->initPlaceholdersValuesFields();
        }

        return implode(', ', $this->placeholders);
    }

    public function getFieldsName(): string
    {
        if (!$this->fields) {
            $this->initPlaceholdersValuesFields();
        }

        return implode(', ', $this->fields);
    }

    public function getFieldsWithPlaceHolders(): string
    {
        if (!$this->placeholders || !$this->fields) {
            $this->initPlaceholdersValuesFields();
        }
        $templateInsert = array_map(static function(string $field, string $placeholder) {
            return sprintf('%s = %s', $field, $placeholder);
        }, $this->fields, $this->placeholders);

        return implode(', ', $templateInsert);
    }

    public function getPlaceholdersWithData(): array
    {
        if (!$this->placeholders || !$this->values) {
            $this->initPlaceholdersValuesFields();
        }
        return array_combine($this->placeholders, $this->values);
    }

    public function fillFrom(array $input): self
    {
        foreach ($this->properties as $property) {
            $val = $input[$property->name] ?? false;
            if (false !== $val) {
                $this->{$property->name} = $val;
            }
        }

        return $this;
    }

    protected function initPlaceholdersValuesFields(): void
    {
        foreach ($this->properties as $property) {
            $this->values[] = $property->getValue($this);
            $prefix = str_replace('\\', '_', $property->class);
            $this->placeholders[] = sprintf(':%s_%s', $prefix, $property->name);
            $this->fields[] = $property->name;
        }
    }
}
