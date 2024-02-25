<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto;

use AgelxNash\MagicDto\Contracts\AttributeResolverInterface;
use AgelxNash\MagicDto\Contracts\MapperInterface;
use AgelxNash\MagicDto\Attributes\ArrayStrict;
use AgelxNash\MagicDto\Exceptions\WrongInputException;
use ReflectionParameter;

class AttributeResolver implements AttributeResolverInterface
{
    public const NATIVE_TYPES = ['int', 'integer', 'bool', 'boolean', 'float', 'string', 'null'];

    /** @var array<MapperInterface> */
    private array $mappers;

    public function __construct(MapperInterface ...$mappers)
    {
        $this->mappers = $mappers;
    }

    public function handle(ReflectionParameter $param, $value)
    {
        if (in_array($param->getType()?->getName(), self::NATIVE_TYPES, true)) {
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
