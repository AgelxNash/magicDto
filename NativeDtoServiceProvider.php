<?php

declare(strict_types=1);

namespace App\Modules\NativeDto;

use App\Modules\NativeDto\Mappers\CarbonMapper;
use App\Modules\NativeDto\Mappers\CollectionMapper;
use App\Modules\NativeDto\Mappers\DtoMapper;
use App\Modules\NativeDto\Mappers\EloquentMapper;
use Illuminate\Support\ServiceProvider;

class NativeDtoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AttributeResolver::class);

        $this->app->when(AttributeResolver::class)
            ->needs(MapperInterface::class)
            ->giveTagged('activeDtoMappers');

        $this->app->tag([
            CarbonMapper::class,
            DtoMapper::class,
            EloquentMapper::class,
            CollectionMapper::class,
        ], 'activeDtoMappers');
    }
}
