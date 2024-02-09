<?php

declare(strict_types=1);

namespace App\Modules\NativeDto;

use Illuminate\Contracts\Support\Arrayable;

interface DtoInterface extends Arrayable
{
    public static function from(array $keys): static;
}
