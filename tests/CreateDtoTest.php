<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests;

use AgelxNash\MagicDto\Contracts\DtoInterface;
use AgelxNash\MagicDto\Exceptions\WrongInputException;
use AgelxNash\MagicDto\Exceptions\WrongTargetException;
use AgelxNash\MagicDto\MagicDto;
use AgelxNash\MagicDto\Tests\DTOs\MainDto;
use AgelxNash\MagicDto\Tests\DTOs\NestedDto;
use AgelxNash\MagicDto\Tests\Models\Account;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use ReflectionClass;

class CreateDtoTest extends TestCase
{
    use RefreshDatabase;

    private MainDto $dto;
    private Account $emailAccount;
    private Account $idAccount;
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->createAccountsTable();

        $this->emailAccount = (new Account())->forceFill([
            'name' => 'Agel_Nash',
            'email' => 'id@example.com',
        ]);
        $this->emailAccount->save();

        $this->idAccount = (new Account())->forceFill([
            'name' => 'AgelxNash',
            'email' => 'email@example.com',
        ]);
        $this->idAccount->save();

        $this->account = (new Account())->forceFill([
            'name' => 'Agel-Nash',
            'email' => 'test@example.com',
        ]);
        $this->account->save();

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
                    description: 'example 2',
                    total: 200
                ),
                new NestedDto(
                    price: 300,
                    count: 5,
                    description: null
                )
            ]),
            emailAccount: $this->emailAccount,
            idAccount: $this->idAccount,
            account: $this->account,
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
                    'total' => '200',
                ],
                [
                    'price' => '300',
                    'count' => '5',
                    'total' => null,
                ],
            ],
            'nullableIntProp' => '300',
            'noExistsProperty' => 'skip',
            'emailAccount' => $this->emailAccount->email,
            'idAccount' => $this->idAccount->getKey(),
            'account' => $this->account->getKey(),
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
                    'total' => '200',
                ],
                [
                    'price' => '300',
                    'count' => '5',
                    'total' => null,
                ],
            ],
            'nullable_int_prop' => '300',
            'no_exists_property' => 'skip',
            'email_account' => $this->emailAccount->email,
            'id_account' => $this->idAccount->getKey(),
            'account' => $this->account->getKey(),
        ]);
    }

    public function testSuccessProperty()
    {
        $dto = NestedDto::from([
            'price' => 100,
            'count' => 3,
            'items' => ['a', 'b', 'c'],
        ]);
        $this->assertInstanceOf(NestedDto::class, $dto::class);
    }

    public function testErrorArrayProperty()
    {
        $dto = NestedDto::from([
            'price' => 100,
            'count' => 3,
            'items' => 'a',
        ]);
        $this->expectException(WrongInputException::class);
    }

    public function testSkippMapperProperty()
    {
        $dto = NestedDto::from([
            'price' => 100,
            'count' => 3,
            'debug' => (object)['field' => 'value'],
        ]);
        $this->assertInstanceOf(NestedDto::class, $dto::class);
    }

    private function check(array $input)
    {
        $dto = MainDto::from($input);

        $this->assertSame([], array_diff_key($this->dto->toArray(), $dto->toArray()));

        $dtoReflection = new ReflectionClass($this->dto);
        foreach ($dtoReflection->getProperties() as $property) {
            $this->assertObjectHasProperty($property->getName(), $dto);
            $nativeValue = $property->getValue($this->dto);
            $fromArrayValue = $dto->{$property->getName()};

            if (is_object($nativeValue)) {
                $this->assertInstanceOf($nativeValue::class, $fromArrayValue);
                match (true) {
                    $nativeValue instanceof CarbonImmutable => $this->assertTrue($nativeValue->equalTo($fromArrayValue)),
                    $nativeValue instanceof Model => $this->assertSame(
                        [],
                        array_diff_key($nativeValue->toArray(), $this->{$property->getName()}->toArray())
                    ),
                    $nativeValue instanceof DtoInterface => $this->assertSame(
                        [],
                        array_diff_key($nativeValue->toArray(), $fromArrayValue->toArray())
                    ),
                    default => null, // skip
                };

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
