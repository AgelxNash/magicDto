<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests\DTOs;

use AgelxNash\MagicDto\Attributes\ArrayStrict;
use AgelxNash\MagicDto\MagicDto;
use stdClass;

class NestedDto extends MagicDto
{
    public function __construct(
        public int $price,
        public int $count,
        public ?string $description,
        public ?int $total = null,
        #[ArrayStrict('string')]
        public array $items = [],
        public ?stdClass $debug = null,
    )
    {
    }
}
