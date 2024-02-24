<?php

declare(strict_types=1);

namespace App\Modules\NativeDto;

use App\Modules\NativeDto\Attributes\ArrayStrict;
use ReflectionParameter;

class AttributeResolver
{
    /** @var array<MapperInterface> */
    private array $mappers;

    public function __construct(MapperInterface ...$mappers)
    {
        $this->mappers = $mappers;
    }

    public function handle(ReflectionParameter $param, $value)
    {
        if (in_array($param->getType()?->getName(), ['int', 'bool', 'float', 'string', 'null'], true)) {
            /** Разрешаем устанавливать null */
            if (is_null($value) && $param->allowsNull()) {
                return $value;
            }

            settype($value, $param->getType()->getName());

            return $value;
        }

        if ($param->getType()?->getName() === 'array') {
            if (!is_array($value)) {
                throw new WrongInputException(sprintf('Ошибочное значение для атрибута %s', $param->name));
            }

            $attribute = $param->getAttributes(ArrayStrict::class)[0] ?? null;
            if ($attribute) {
                $value = array_map(static function ($item) use ($attribute) {
                    return $attribute->newInstance()->handle($item);
                }, $value);
            }

            return $value;
        }

        foreach ($this->mappers as $mapper) {
            if ($mapper->canApply($param, $value)) {
                return $mapper->apply($param, $value);
            }
        }

        return $value;
    }
}
