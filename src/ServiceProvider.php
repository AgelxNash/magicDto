<?php

declare(strict_types=1);

namespace AgelxNash\MagicDto;

use AgelxNash\MagicDto\Contracts\AttributeResolverInterface;
use AgelxNash\MagicDto\Contracts\MapperInterface;
use AgelxNash\MagicDto\Mappers\CarbonMapper;
use AgelxNash\MagicDto\Mappers\CollectionMapper;
use AgelxNash\MagicDto\Mappers\DtoMapper;
use AgelxNash\MagicDto\Mappers\EloquentMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected array $defaultMappers = [
        DtoMapper::class,
        CollectionMapper::class,
        CarbonMapper::class,
    ];

    public function register(): void
    {
        $this->app->singleton(AttributeResolverInterface::class, AttributeResolver::class);
        $this->app->when(AttributeResolver::class)
            ->needs(MapperInterface::class)
            ->giveTagged('activeDtoMappers');

        $this->app->tag($this->getMappers(), 'activeDtoMappers');
    }

    private function getMappers(): array
    {
        $mappers = $this->defaultMappers;

        if (class_exists(Model::class)) {
            $mappers[] = EloquentMapper::class;
        }

        return $mappers;
    }
}
