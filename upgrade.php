<?php

declare(strict_types=1);

use PHPStan\Type\VoidType;
use Rector\Composer\Rector\ChangePackageVersionComposerRector;
use Rector\Composer\ValueObject\PackageAndVersion;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

function upgradeEventSauceFrom0to1(ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ChangePackageVersionComposerRector::class)
        ->call('configure', [[
            ChangePackageVersionComposerRector::PACKAGES_AND_VERSIONS => ValueObjectInliner::inline([
                new PackageAndVersion('eventsauce/eventsauce', 'dev-version/1.0.0'),
            ]),
        ]]);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->call('configure', [[
            AddReturnTypeDeclarationRector::METHOD_RETURN_TYPES => ValueObjectInliner::inline([
                new AddReturnTypeDeclaration('EventSauce\\EventSourcing\\Consumer', 'handle', new VoidType()),
                new AddReturnTypeDeclaration('EventSauce\\EventSourcing\\MessageRepository', 'persist', new VoidType()),
                new AddReturnTypeDeclaration('EventSauce\\EventSourcing\\MessageRepository', 'persistEvents', new VoidType()),
            ])
        ]]);
    $services->set(RenameNamespaceRector::class)
        ->call('configure', [[
            RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => [
                'EventSauce\\EventSourcing\\Time\\' => 'EventSauce\\Clock\\',
            ]
        ]]);

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                'EventSauce\\EventSourcing\\Consumer' => 'EventSauce\\EventSourcing\\MessageConsumer',
                'EventSauce\\EventSourcing\\AggregateRootTestCase' => 'EventSauce\\EventSourcing\\TestUtilities\\AggregateRootTestCase',
            ]
        ]]);

    $services->set(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename('EventSauce\\Clock\\Clock', 'dateTime', 'now'),
                new MethodCallRename('EventSauce\\Clock\\TestClock', 'dateTime', 'now'),
                new MethodCallRename('EventSauce\\EventSourcing\\Time\\Clock', 'dateTime', 'now'),
                new MethodCallRename('EventSauce\\EventSourcing\\Time\\TestClock', 'dateTime', 'now'),
                new MethodCallRename('EventSauce\\EventSourcing\\Time\\SystemClock', 'dateTime', 'now'),
                new MethodCallRename('EventSauce\\EventSourcing\\Time\\SystemTestClock', 'dateTime', 'now'),
            ])
        ]]);
}
