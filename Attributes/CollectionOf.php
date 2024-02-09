<?php

declare(strict_types=1);

namespace App\Modules\NativeDto\Attributes;

use App\Modules\NativeDto\DtoInterface;
use App\Modules\NativeDto\WrongTargetException;
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
