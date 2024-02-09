<?php

declare(strict_types=1);

namespace App\Modules\NativeDto\Attributes;

use App\Modules\NativeDto\WrongTargetException;
use Attribute;
use KDServices\Core\Http\Requests\Api\Request;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class ReplaceRequest
{
    public function __construct(
        /** @var class-string<Request> $class */
        public string $class,
    ) {
        if (! is_subclass_of($this->class, Request::class)) {
            throw new WrongTargetException(sprintf(
                'Class %s given does not implement `%s`',
                $this->class,
                Request::class
            ));
        }
        dd('q');
    }

    public function handle(string $where)
    {
        return $this->class::query()->where($this->field, '=', $where)->first();
    }
}
