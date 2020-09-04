<?php

declare(strict_types=1);

namespace Core;

final class BaseCollectionWhereExpression
{
    private $where;

    public function add(string $fieldName, $value, string $compare = '=', string $condition = 'AND'): self
    {
        if (!empty($fieldName)) {
            $prefix = uniqid('', false).'_';
            $this->where[] = [
                'exp' => "{$fieldName} {$compare} :{$prefix}{$fieldName}",
                'cond' => $condition,
                'param' => ":{$prefix}{$fieldName}",
                'value' => $value,
            ];
        }

        return $this;
    }

    public function __toString(): string
    {
        $result = '';
        foreach ($this->where as $index => $where) {
            if (0 === $index) {
                $result .= $where['exp'];
            } else {
                $result .= ' '.$where['cond'].' '.$where['exp'];
            }
        }

        return ' WHERE '.$result;
    }

    public function getPlaceholdersWithData(): array
    {
        $result = [];
        foreach ($this->where as $index => $where) {
            $result[$where['param']] = $where['value'];
        }

        return $result;
    }

}
