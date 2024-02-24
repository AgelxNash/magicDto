<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Mappers;

use AgelxNash\MagicDto\Contracts\DtoInterface;
use AgelxNash\MagicDto\Contracts\MapperInterface;
use ReflectionClass;
use ReflectionParameter;

class DtoMapper implements MapperInterface
{
    public function canApply(ReflectionParameter $param, $value): bool
    {
        return $param->getType()->getName() === DtoInterface::class ||
            (new ReflectionClass($param->getType()->getName()))->isSubclassOf(DtoInterface::class);
    }

    public function apply(ReflectionParameter $param, $value)
    {
        return ($param->getType()->getName())::from($value);
    }
}
