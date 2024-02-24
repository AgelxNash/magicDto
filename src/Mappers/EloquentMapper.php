<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Mappers;

use AgelxNash\MagicDto\Attributes\InjectModel;
use AgelxNash\MagicDto\Contracts\MapperInterface;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionParameter;

class EloquentMapper implements MapperInterface
{
    public function canApply(ReflectionParameter $param, $value): bool
    {
        return $param->getType()->getName() === Model::class ||
            (new ReflectionClass($param->getType()->getName()))->isSubclassOf(Model::class);
    }

    public function apply(ReflectionParameter $param, $value)
    {
        $attribute = $param->getAttributes(InjectModel::class)[0] ?? null;
        if ($attribute) {
            return $attribute->newInstance()->handle($value);
        }

        return ($param->getType()->getName())::findOrFail($value);
    }
}
