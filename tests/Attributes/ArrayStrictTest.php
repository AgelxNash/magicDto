<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Tests\Attributes;

use AgelxNash\MagicDto\Attributes\ArrayStrict;
use AgelxNash\MagicDto\Exceptions\WrongTargetException;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class ArrayStrictTest extends TestCase
{
    public function testInt()
    {
        $object = new ArrayStrict('int');
        $map = [
            null => 0,
            0 => 0,
            false => 0,
            true => 1,
            'test' => 0,
            '100' => 100,
            '0700' => 700,
            123 => 123,
        ];
        foreach ($map as $input => $output) {
            $this->assertSame(
                $output,
                $object->handle($input),
                var_export($input, true) . ' => ' . var_export($output, true)
            );
        }

        $this->assertSame(2023, $object->handle(Carbon::parse('2023-02-22')));
        $this->assertSame(0, $object->handle([]), 'Empty array');
        $this->assertSame(1, $object->handle(['not_empty_array']), 'Not empty array');
    }

    public function testString()
    {
        $object = new ArrayStrict('string');
        $map = [
            null => '',
            0 => '0',
            false => '',
            true => '1',
            'test' => 'test',
            '100' => '100',
            700 => '700',
            0.5 => '0',
        ];
        foreach ($map as $input => $output) {
            $this->assertSame(
                $output,
                $object->handle($input),
                var_export($input, true) . ' => ' . var_export($output, true)
            );
        }

        $this->assertSame('2023-02-22 00:00:00', $object->handle(Carbon::parse('2023-02-22')));
        $this->assertSame('', $object->handle([]), 'Empty array');
        $this->assertSame('', $object->handle(['not_empty_array']), 'Not empty array');
    }

    public function testBoolean()
    {
        $object = new ArrayStrict('boolean');
        $map = [
            null => false,
            0 => false,
            false => false,
            true => true,
            'test' => true,
            '100' => true,
            700 => true,
            0.5 => false,
        ];
        foreach ($map as $input => $output) {
            $this->assertSame(
                $output,
                $object->handle($input),
                var_export($input, true) . ' => ' . var_export($output, true)
            );
        }

        $this->assertTrue($object->handle(Carbon::parse('2023-02-22')));
        $this->assertFalse($object->handle([]), 'Empty array');
        $this->assertTrue($object->handle(['not_empty_array']), 'Not empty array');
    }

    public function testFloat()
    {
        $object = new ArrayStrict('float');
        $map = [
            null => 0.0,
            0 => 0.0,
            false => 0.0,
            true => 1.0,
            'test' => 0.0,
            '100' => 100.0,
            700 => 700.0,
            '0.5' => 0.5,
            '0,5' => 0.0
        ];
        foreach ($map as $input => $output) {
            $this->assertSame(
                $output,
                $object->handle($input),
                var_export($input, true) . ' => ' . var_export($output, true)
            );
        }

        $this->assertSame(2023.0, $object->handle(Carbon::parse('2023-02-22')));
        $this->assertSame(0.0, $object->handle([]), 'Empty array');
        $this->assertSame(1.0, $object->handle(['not_empty_array']), 'Not empty array');
    }

    public function testNull()
    {
        $object = new ArrayStrict('null');
        $map = [
            null, 0, false, true, 'test', '100', 700, '0.5', '0,5', 0.0, [], ['not_empty_array'], Carbon::parse('2023-02-22')
        ];
        foreach ($map as $input) {
            $this->assertNull(
                $object->handle($input),
                var_export($input, true)
            );
        }
    }

    public function testCarbonImmutable()
    {
        $object = new ArrayStrict(CarbonImmutable::class);
        $map = [null, 0, false, true, 'test', '100', '0700', 123, [], ['not_empty_array'], CarbonImmutable::parse('2023-02-22')];

        foreach ($map as $input => $output) {
            $this->assertInstanceOf(CarbonImmutable::class, $object->handle($input));
        }
    }

    public function testCarbon()
    {
        $object = new ArrayStrict(Carbon::class);
        $map = [null, 0, false, true, 'test', '100', '0700', 123, [], ['not_empty_array'], Carbon::parse('2023-02-22')];

        foreach ($map as $input => $output) {
            $this->assertInstanceOf(Carbon::class, $object->handle($input));
        }
    }

    public function testExcept()
    {
        $this->expectException(WrongTargetException::class);
        new ArrayStrict('WTF?');
    }
}
