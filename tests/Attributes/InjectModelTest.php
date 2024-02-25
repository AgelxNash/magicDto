<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests\Attributes;

use AgelxNash\MagicDto\Attributes\InjectModel;
use AgelxNash\MagicDto\Exceptions\WrongTargetException;
use AgelxNash\MagicDto\Tests\Models\Account;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AgelxNash\MagicDto\Tests\TestCase;

class InjectModelTest extends TestCase
{
    use RefreshDatabase;

    public function testExcept()
    {
        $this->expectException(WrongTargetException::class);
        new InjectModel('WTF?');

        $this->expectException(WrongTargetException::class);
        new InjectModel(Carbon::class);
    }

    public function testModelById()
    {
        $this->loadLaravelMigrations();
        $this->createAccountsTable();

        $user = (new Account())->forceFill([
            'name' => 'Agel_Nash',
            'email' => 'email@example.com',
        ]);
        $user->save();
        /** @var Account $object */
        $object = (new InjectModel(Account::class))->handle($user->getKey());
        $this->assertInstanceOf(Account::class, $object);
        $this->assertSame($user->name, $object->name);
        $this->assertSame($user->email, $object->email);
        $this->assertSame($user->getKey(), $object->getKey());
    }

    public function testModelByEmail()
    {
        $this->loadLaravelMigrations();
        $this->createAccountsTable();

        $user = (new Account())->forceFill([
            'name' => 'Agel_Nash',
            'email' => 'email@example.com',
        ]);
        $user->save();
        /** @var Account $object */
        $object = (new InjectModel(Account::class, 'email'))->handle($user->email);
        $this->assertInstanceOf(Account::class, $object);
        $this->assertSame($user->name, $object->name);
        $this->assertSame($user->email, $object->email);
        $this->assertSame($user->getKey(), $object->getKey());
    }

    public function testModelByName()
    {
        $this->loadLaravelMigrations();
        $this->createAccountsTable();

        $user = (new Account())->forceFill([
            'name' => 'Agel_Nash',
            'email' => 'email@example.com',
        ]);
        $user->save();
        /** @var Account $object */
        $object = (new InjectModel(Account::class, 'name'))->handle($user->name);
        $this->assertInstanceOf(Account::class, $object);
        $this->assertSame($user->name, $object->name);
        $this->assertSame($user->email, $object->email);
        $this->assertSame($user->getKey(), $object->getKey());
    }
}
