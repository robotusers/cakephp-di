# CakePHP DI

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![codecov](https://codecov.io/gh/robotusers/cakephp-di/branch/master/graph/badge.svg)](https://codecov.io/gh/robotusers/cakephp-di)

PSR-11 compatible CakePHP Dependency Injection Container abstraction plugin

## Under development

The plugin is under development and not intended to use in production yet.

## Versions

For CakePHP 3.x use CakePHP DI 0.1.x versions. CakePHP DI 0.2.x is intended for CakePHP 4.0 and 4.1

For CakePHP 4.2 try [0.3](https://github.com/robotusers/cakephp-di/tree/0.3) branch

## Container Abstraction

This plugin provides tools for using any PSR-11 compatible DIC with CakePHP framework.
DIC support for CakePHP is a very frequently requested feature. Although CakePHP 
is build with Dependency Injection in mind, it does not provide any build-in DIC. 
There are many great DI Containers out there and this plugin allows you to choose 
the one you like the most and use it with your CakePHP app.

## Configuration

CakePHP DI plugin provides a `Robotusers\DI\Core\ContainerApplicationInterface` 
that your `Application` class should implement. This interface defines a 
`getContainer()` method that should return your PSR-11 compatible container.

The plugin provides a base application class that you can extend.

```php
class Application extends \Robotusers\DI\Http\BaseApplication
{
    protected function createContainer()
    {
        $container = new SomeContainer();

        //configure your services

        return $container;
    }
}
```

Note that the base class requires you to implement `createContainer()` method. 
That is a factory method for your container as `getContainer()` needs to return 
the same instance on each call.

The `BaseApplication` class also provides some wiring for action dispatcher so 
the controllers and actions use your DI Container.

## Controllers

Controllers should be registered as a service in your DIC. The plugin tries to 
retrieve a controller from your DIC with the controller's FQCN as an id.
For example: `$container->get('App\Controller\ArticlesController')`;

The plugin also provides the ability to inject services into controller actions.

The services must be passed as a parameters to the action method. The precedence 
take the passed parameters, so for example your `view` method should look like this:

```php
//ArticlesController.php

public function view($id, ArticlesServiceInterface $service)
{
    //code
}
```

The `ArticlesServiceInterface` instance will be injected into the method.

## Console

In order to fetch a console command from a DIC you need to use a `CommandFactory` provided
with this plugin.

In your `bin/cake.php`:

```php
...

use App\Application;
use Cake\Console\CommandRunner;
use Robotusers\Di\Console\CommandFactory;

$application = new Application(dirname(__DIR__) . '/config');
$factory = new CommandFactory($application);
$runner = new CommandRunner($application, 'cake', $factory);
exit($runner->run($argv));
```

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
