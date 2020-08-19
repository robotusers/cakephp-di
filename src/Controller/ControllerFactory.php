<?php
declare(strict_types=1);

/*
 * The MIT License
 *
 * Copyright 2017 Robert Pustułka <robert.pustulka@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Robotusers\Di\Controller;

use Cake\Controller\Controller;
use Cake\Controller\ControllerFactory as BaseControllerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

class ControllerFactory extends BaseControllerFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container PSR Container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function create(ServerRequestInterface $request): Controller
    {
        $className = $this->getControllerClass($request);
        if ($className === null) {
            $this->missingController($request);
        }

        /** @psalm-suppress PossiblyNullArgument */
        $reflection = new ReflectionClass($className);
        if ($reflection->isAbstract()) {
            $this->missingController($request);
        }

        /** @var Controller $controller */
        $controller = $this->container->get((string)$className);
        $controller->setRequest($request);

        return $controller;
    }

    public function invoke($controller): ResponseInterface
    {
        $result = $controller->startupProcess();
        if ($result instanceof ResponseInterface) {
            return $result;
        }

        $action = $controller->getAction();
        $args = $this->getArgs($controller);
        $controller->invokeAction($action, $args);

        $result = $controller->shutdownProcess();
        if ($result instanceof ResponseInterface) {
            return $result;
        }

        return $controller->getResponse();
    }

    private function getArgs($controller): array
    {
        $request = $controller->getRequest();
        $action = $request->getParam('action');

        $reflector = new ReflectionMethod($controller, $action);
        /** @var ReflectionParameter[] $parameters */
        $parameters = $reflector->getParameters();
        $passed = $request->getParam('pass');
        $args = [];
        $i = 0;
        foreach ($parameters as $parameter) {
            if (isset($passed[$i])) {
                $args[] = $passed[$i];
            } else {
                $class = $parameter->getClass();
                if ($class) {
                    $id = $class->getName();
                } else {
                    $id = $parameter->getName();
                }
                if ($this->container->has($id)) {
                    $args[] = $this->container->get($id);
                }
            }
            $i++;
        }

        return $args;
    }
}
