<?php

declare(strict_types=1);

namespace App\Modules\NativeDto\Mappers;

use App\Modules\NativeDto\MapperInterface;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use ReflectionClass;
use ReflectionParameter;

class CarbonMapper implements MapperInterface
{
    public function canApply(ReflectionParameter $param, $value): bool
    {
        return (new ReflectionClass($param->getType()->getName()))
            ->isSubclassOf(CarbonInterface::class);
    }

    public function apply(ReflectionParameter $param, $value)
    {
        return CarbonImmutable::parse($value);
    }
}
