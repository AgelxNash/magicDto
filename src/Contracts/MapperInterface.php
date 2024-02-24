<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Contracts;

use ReflectionParameter;

interface MapperInterface
{
    public function canApply(ReflectionParameter $param, $value): bool;

    public function apply(ReflectionParameter $param, $value);
}
