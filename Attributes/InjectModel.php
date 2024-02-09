<?php

declare(strict_types=1);

namespace App\Modules\NativeDto\Attributes;

use App\Modules\NativeDto\WrongTargetException;
use Attribute;
use Illuminate\Database\Eloquent\Model;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class InjectModel
{
    public function __construct(
        /** @var class-string<Model> $class */
        public string $class,
        public string $field = 'id',
    ) {
        if (! is_subclass_of($this->class, Model::class)) {
            throw new WrongTargetException(sprintf(
                'Class %s given does not implement `%s`',
                $this->class,
                Model::class
            ));
        }
    }

    public function handle(string $where)
    {
        return $this->class::query()->where($this->field, '=', $where)->first();
    }
}
