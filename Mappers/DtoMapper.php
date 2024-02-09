<?php

declare(strict_types=1);

namespace App\Modules\NativeDto\Mappers;

use App\Modules\NativeDto\DtoInterface;
use App\Modules\NativeDto\MapperInterface;
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
