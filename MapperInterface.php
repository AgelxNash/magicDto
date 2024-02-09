<?php

declare(strict_types=1);

namespace App\Modules\NativeDto;

use ReflectionParameter;

interface MapperInterface
{
    public function canApply(ReflectionParameter $param, $value): bool;

    public function apply(ReflectionParameter $param, $value);
}
