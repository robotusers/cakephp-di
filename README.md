# CakePHP DI

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![codecov](https://codecov.io/gh/robotusers/cakephp-di/branch/master/graph/badge.svg)](https://codecov.io/gh/robotusers/cakephp-di)

PSR-11 compatible CakePHP Dependency Injection Container abstraction plugin

## Under development

The plugin is under development and not intended to use in production yet.

## Versions

Versions 0.3.x are dedicated for CakePHP 4.2 which already has built in DIC support.
This version removes application DIC wiring as methods introduced in CakePHP 4.2
are in conflict with this plugin.

For CakePHP 3.x use 0.1.x versions. 0.2.x is intended for CakePHP 4.0 and 4.1.

## Container Abstraction

This plugin provides tools for using any PSR-11 compatible DIC with CakePHP framework.
DIC support for CakePHP is a very frequently requested feature. Although CakePHP 
is build with Dependency Injection in mind, it does not provide any build-in DIC. 
There are many great DI Containers out there and this plugin allows you to choose 
the one you like the most and use it with your CakePHP app.

## Table Locator

Extending `Robotusers\DI\Http\BaseApplication` provides you with a DIC aware
implementation of `TableLocator`. During the bootstrapping process the global
instance of table locator is injected into `TableRegistry`.

Table locator replacement shipped with this plugin allows you to inject your own
table factory.

By default a `ContainerFactory` is used which retrieves a table from your DIC using
table's class name as an `$id`. Note that options are not passed to the DIC as PSR-11
implementation does not support passing extrac arguments to the `get()` method.

You either need to configure your table options using `TableLocator::setConfig()`
method or configure your container to pass correct options.

You can also use custom implementation of table factory by overriding 
`Application::createTableLocator()` method. Table factory must be a callable that
accepts `$options` array.

```php
protected function createTableLocator()
{
    $factory = function($options) {
        // retrieve a table from your DIC

        return $table;
    };

    return new \Robotusers\Di\ORM\Locator\TableLocator($factory);
}
```
