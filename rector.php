<?php

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    upgradeEventSauceFrom0to1($containerConfigurator);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [SetList::CODING_STYLE]);
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_80);
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, true);
    $parameters->set(Option::IMPORT_DOC_BLOCKS, true);
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/src/composer.json']);

    $parameters->set(Option::ENABLE_CACHE, false);

    $services = $containerConfigurator->services();
//    $services->set(UseAddingPostRector::class);
//    $services->set(ShortNameResolver::class);
//    $services->set(NameImportingPostRector::class);
//    $services->set(ShortNameResolver::class);
};
