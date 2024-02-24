<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface DtoInterface extends Arrayable
{
    public static function from(array $keys): static;
}
