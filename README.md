# CakePHP DI

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Build Status](https://travis-ci.org/robotusers/cakephp-di.svg?branch=master)](https://travis-ci.org/robotusers/cakephp-di)
[![codecov](https://codecov.io/gh/robotusers/cakephp-di/branch/master/graph/badge.svg)](https://codecov.io/gh/robotusers/cakephp-di)

PSR-11 compatible CakePHP Dependency Injection Container abstraction plugin

## Under development

The plugin is under heavy development and not intended to use yet.

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

*TODO*
