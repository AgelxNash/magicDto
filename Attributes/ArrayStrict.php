<?php

declare(strict_types=1);

namespace App\Modules\NativeDto\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class ArrayStrict
{
    public function __construct(public string $type)
    {
    }

    public function handle($data)
    {
        settype($data, $this->type);

        return $data;
    }
}
