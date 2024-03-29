<?php

declare(strict_types=1);

use App\Application\Directory\Locale;
use App\Application\Directory\LocaleInterface;
use App\Application\SearchCriteriaInterface;
use App\Application\SearchResultInterface;
use App\Application\SearchResultPageInterface;
use App\Domain\PermissionRepositoryInterface;
use App\Domain\RoleRepositoryInterface;
use App\Domain\UserRepositoryInterface;
use App\Infrastructure\Database\Persistence\PermissionRepository;
use App\Infrastructure\Database\Persistence\RoleRepository;
use App\Infrastructure\Database\Persistence\UserRepository;
use App\Infrastructure\Database\Query\SearchCriteria;
use App\Infrastructure\SearchResult;
use App\Infrastructure\SearchResultPage;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LocaleInterface::class => autowire(Locale::class),
        UserRepositoryInterface::class => autowire(UserRepository::class),
        RoleRepositoryInterface::class => autowire(RoleRepository::class),
        PermissionRepositoryInterface::class => autowire(PermissionRepository::class),
        SearchCriteriaInterface::class => autowire(SearchCriteria::class),
        SearchResultInterface::class => autowire(SearchResult::class),
        SearchResultPageInterface::class => autowire(SearchResultPage::class),
    ]);
};
