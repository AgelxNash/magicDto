<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Mappers;

use AgelxNash\MagicDto\Attributes\CollectionOf;
use AgelxNash\MagicDto\Contracts\MapperInterface;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionParameter;

class CollectionMapper implements MapperInterface
{
    public function canApply(ReflectionParameter $param, $value): bool
    {
        return $param->getType()->getName() === Collection::class ||
            (new ReflectionClass($param->getType()->getName()))->isSubclassOf(Collection::class);
    }

    public function apply(ReflectionParameter $param, $value)
    {
        $value = collect($value);
        $attribute = $param->getAttributes(CollectionOf::class)[0] ?? null;
        if ($attribute) {
            $reflector = $attribute->newInstance();
            $value = $value->map(static function ($value) use ($reflector) {
                return $reflector->handle($value);
            });
        }

        return $value;
    }
}
