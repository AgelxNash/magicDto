<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests;

use AgelxNash\MagicDto\ServiceProvider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function createAccountsTable()
    {
        (new class extends Migration
        {
            public function up()
            {
                Schema::create('accounts', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name')->index();
                    $table->string('email')->nullable();
                    $table->timestamps();
                });
            }
        })->up();
    }
}
