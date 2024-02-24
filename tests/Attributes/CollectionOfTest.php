<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests\Attributes;

use AgelxNash\MagicDto\Attributes\CollectionOf;
use AgelxNash\MagicDto\Exceptions\WrongTargetException;
use PHPUnit\Framework\TestCase;

class CollectionOfTest extends TestCase
{
    public function testExcept()
    {
        $this->expectException(WrongTargetException::class);
        new CollectionOf('WTF?');
    }
}
