<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests;

use AgelxNash\MagicDto\Examples\MainDto;
use AgelxNash\MagicDto\Examples\NestedDto;
use AgelxNash\MagicDto\ServiceProvider;
use Carbon\CarbonImmutable;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CreateDtoTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        (new ServiceProvider(Container::getInstance()))->register();

        $this->dto = new MainDto(
            intProp: 1,
            stringProp: 'test',
            arrayProp: ['a', '100'],
            strictArrayProp: [1, 10, 500],
            nestedDto: new NestedDto(
                price: 100,
                count: 2,
                description: 'example 1'
            ),
            date: CarbonImmutable::parse('2023-06-08'),
            dateTime: CarbonImmutable::parse('2023-06-08 12:25:50'),
            nullableIntProp: 300,
            collectionNestedDto: new Collection([
                new NestedDto(
                    price: 200,
                    count: 1,
                    description: 'example 2'
                ),
                new NestedDto(
                    price: 300,
                    count: 5,
                    description: null
                )
            ]),
        );
    }

    public function testBase()
    {
        $this->check([
            'intProp' => '1',
            'stringProp' => 'test',
            'date' => '2023-06-08',
            'dateTime' => '2023-06-08 12:25:50',
            'arrayProp' => ['a', '100'],
            'strictArrayProp' => ['1', '10', '500'],
            'nestedDto' => [
                'price' => '100',
                'count' => '2',
                'description' => 'example 1',
            ],
            'collectionNestedDto' => [
                [
                    'price' => '200',
                    'count' => '1',
                    'description' => 'example 2',
                ],
                [
                    'price' => '300',
                    'count' => '5',
                ],
            ],
            'nullableIntProp' => '300',
            'noExistsProperty' => 'skip',
        ]);
    }

    public function testSnakeToCamel()
    {
        $this->check([
            'int_prop' => '1',
            'string_prop' => 'test',
            'date' => '2023-06-08',
            'date_time' => '2023-06-08 12:25:50',
            'array_prop' => ['a', '100'],
            'strict_array_prop' => ['1', '10', '500'],
            'nested_dto' => [
                'price' => '100',
                'count' => '2',
                'description' => 'example 1',
            ],
            'collection_nested_dto' => [
                [
                    'price' => '200',
                    'count' => '1',
                    'description' => 'example 2',
                ],
                [
                    'price' => '300',
                    'count' => '5',
                ],
            ],
            'nullable_int_prop' => '300',
            'no_exists_property' => 'skip',
        ]);
    }

    private function check(array $input)
    {
        $dto = MainDto::from($input);

        $this->assertSame($this->dto->toArray(), $dto->toArray());

        $dtoReflection = new ReflectionClass($this->dto);
        foreach ($dtoReflection->getProperties() as $property) {
            $this->assertObjectHasProperty($property->getName(), $dto);
            $nativeValue = $property->getValue($this->dto);
            $fromArrayValue = $dto->{$property->getName()};

            if (is_object($nativeValue)) {
                $this->assertInstanceOf($nativeValue::class, $fromArrayValue);
                if ($nativeValue instanceof CarbonImmutable) {
                    $nativeValue->equalTo($fromArrayValue);
                }
                continue;
            }

            $this->assertSame(
                $nativeValue,
                $fromArrayValue,
                var_export($nativeValue, true) . ' => ' . var_export($fromArrayValue, true)
            );
        }
    }
}
