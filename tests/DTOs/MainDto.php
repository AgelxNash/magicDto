<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests\DTOs;

use AgelxNash\MagicDto\Attributes\InjectModel;
use AgelxNash\MagicDto\MagicDto;
use AgelxNash\MagicDto\Attributes\ArrayStrict;
use AgelxNash\MagicDto\Attributes\CollectionOf;
use AgelxNash\MagicDto\Tests\Models\Account;
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
        #[InjectModel(Account::class)]
        public Account $idAccount,
        #[InjectModel(Account::class, 'email')]
        public Account $emailAccount,
        public ?int $nullableIntDefaultProp = null,
        public ?NestedDto $nullableNestedDto = null,
        #[CollectionOf(NestedDto::class)]
        public ?Collection $collectionNestedDto = null,
    )
    {
    }
}
