<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Examples;

use AgelxNash\MagicDto\MagicDto;

class NestedDto extends MagicDto
{
    public function __construct(
        public int $price,
        public int $count,
        public ?string $description
    )
    {

    }
}
