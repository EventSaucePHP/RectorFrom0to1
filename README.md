# EventSauce 0.8 to 1.0 Rector Set

This package provides a rector configuration to help you
migrate from version 0.8 to version 1.0. All the interface
changes, renamed, and added return types have been taken
care of.

## Usage

```bash
composer require --dev eventsauce/rector-0-to-1
```

Now use the `` function in your rector configuration:

```php
<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    upgradeEventSauceFrom0to1($containerConfigurator);

    // your other rector configuration settings
};
```
