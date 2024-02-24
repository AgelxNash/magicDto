<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto\Attributes;

use AgelxNash\MagicDto\Exceptions\WrongTargetException;
use AgelxNash\MagicDto\MagicDto;
use Attribute;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Stringable;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class ArrayStrict
{
    private array $allowedTypes = [
        'int',
        'integer',
        'bool',
        'boolean',
        'float',
        'string',
        'null',
        CarbonImmutable::class,
        Carbon::class,
    ];

    public function __construct(public string $type)
    {
        if (!in_array($this->type, $this->allowedTypes, true)) {
            throw new WrongTargetException(sprintf('Type %s given does not allowed', $this->type));
        }
    }

    public function handle($data)
    {
        if ($data instanceof Stringable) {
            $data = $data->__toString();
        }

        if ($this->type === CarbonImmutable::class) {
            return CarbonImmutable::parse($data);
        }

        if ($this->type === Carbon::class) {
            return Carbon::parse($data);
        }

        if ($this->type === 'string' && is_array($data)) {
            return '';
        }

        settype($data, $this->type);

        return $data;
    }
}
