<?php

declare(strict_types=1);

namespace App\Modules\NativeDto;

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

        if (!(new ReflectionClass($dtoClass))->implementsInterface(DtoInterface::class)) {
            throw new LogicException('Не задан нужный интерфейс');
        }

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

            $value = $keys[$param->name];
            $params[$param->name] =
                $param->getType() === null ? $value : app(AttributeResolver::class)->handle($param, $value);
        }

        return new $dtoClass(...$params);
    }

    public function toArray(): array
    {
        return array_map(static function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, (array)$this);
    }

    public static function renameKeys(array $data, callable $method): array
    {
        return array_merge(...array_map(static function ($key, $value) use ($method) {
            return [$method($key) => $value];
        }, array_keys($data), $data));
    }
}
