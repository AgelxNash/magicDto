<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Examples;

use AgelxNash\MagicDto\MagicDto;
use AgelxNash\MagicDto\Attributes\ArrayStrict;
use AgelxNash\MagicDto\Attributes\CollectionOf;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class MainDto extends MagicDto
{
    public function __construct(
        public int $intProp,
        public string $stringProp,
        public array $arrayProp,
        #[ArrayStrict('int')]
        public array $strictArrayProp,
        public NestedDto $nestedDto,
        public CarbonImmutable $date,
        public CarbonImmutable $dateTime,
        public ?int $nullableIntProp,
        public ?int $nullableIntDefaultProp = null,
        public ?NestedDto $nullableNestedDto = null,
        #[CollectionOf(NestedDto::class)]
        public ?Collection $collectionNestedDto = null
    )
    {
    }
}
