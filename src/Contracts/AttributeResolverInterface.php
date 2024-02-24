<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Contracts;

use ReflectionParameter;

interface AttributeResolverInterface
{
    public function handle(ReflectionParameter $param, $value);
}
