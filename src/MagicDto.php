<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto;

use AgelxNash\MagicDto\Contracts\AttributeResolverInterface;
use AgelxNash\MagicDto\Contracts\DtoInterface;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use LogicException;
use ReflectionClass;
use ReflectionMethod;

abstract class MagicDto implements DtoInterface
{
    public static function from(array $keys): static
    {
        $dtoClass = static::class;

        /** Переименовываем ключи массива перед тем, как создать DTO */
        $keys = self::renameKeys($keys, static fn($key) => Str::camel($key));

        $params = [];
        $rConstructor = new ReflectionMethod($dtoClass, '__construct');
        foreach ($rConstructor->getParameters() as $param) {
            if (!array_key_exists($param->name, $keys)) {
                if ($param->isDefaultValueAvailable()) {
                    $params[$param->name] = $param->getDefaultValue();
                } elseif ($param->allowsNull()) {
                    $params[$param->name] = null;
                }

                continue;
            }

            $params[$param->name] = Container::getInstance()
                ->get(AttributeResolverInterface::class)
                ->handle($param, $keys[$param->name]);
        }

        return new $dtoClass(...$params);
    }

    public function toArray(): array
    {
        return array_map(static function ($value) {
            if ($value instanceof Arrayable) {
                return $value->toArray();
            }

            if ($value instanceof \Stringable) {
                return $value->__toString();
            }

            return $value;
        }, (array)$this);
    }

    public static function renameKeys(array $data, callable $method): array
    {
        return array_merge(...array_map(static function ($key, $value) use ($method) {
            return [$method($key) => $value];
        }, array_keys($data), $data));
    }
}
