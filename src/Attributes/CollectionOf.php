<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Attributes;

use AgelxNash\MagicDto\Contracts\DtoInterface;
use AgelxNash\MagicDto\Exceptions\WrongTargetException;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class CollectionOf
{
    public function __construct(
        /** @var class-string<DtoInterface> $class */
        public string $class,
    ) {
        if (! is_subclass_of($this->class, DtoInterface::class)) {
            throw new WrongTargetException(sprintf(
                'Class %s given does not implement `%s`',
                $this->class,
                DtoInterface::class
            ));
        }
    }

    public function handle($data): DtoInterface
    {
        return $this->class::from($data);
    }
}
