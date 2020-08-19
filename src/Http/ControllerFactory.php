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
namespace Robotusers\Di\Http;

use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Http\ControllerFactory as BaseControllerFactory;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use LogicException;
use Psr\Container\ContainerInterface;
use ReflectionMethod;

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
    public function create(ServerRequest $request, Response $response)
    {
        $className = $this->getControllerClass($request);
        if ($className === null) {
            $this->missingController($request);
        }
        /** @var \Robotusers\Di\Http\Controller $controller */
        $controller = $this->container->get((string)$className);
        $controller->setRequest($request);
        $controller->response = $response;

        return $controller;
    }

    /**
     * Dispatches the controller action. Checks that the action
     * exists and isn't private.
     *
     * @param Controller $controller Controller.
     * @return mixed The resulting response.
     * @throws LogicException When request is not set.
     * @throws MissingActionException When actions are not defined or inaccessible.
     */
    public function invokeAction(Controller $controller)
    {
        $request = $controller->request;
        if (!$request) {
            throw new LogicException('No Request object configured. Cannot invoke action');
        }
        $action = $request->getParam('action');
        if (!method_exists($controller, $action)) {
            return $controller->invokeAction();
        }
        if (!$controller->isAction($action)) {
            throw new MissingActionException(['controller' => $controller->getName() . 'Controller', 'action' => $request->getParam('action'), 'prefix' => $request->getParam('prefix') ?: '', 'plugin' => $request->getParam('plugin')]);
        }
        $reflector = new ReflectionMethod($controller, $action);
        /** @var \Robotusers\Di\Http\ReflectionParameter[] $parameters */
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
        /** @var callable $callable */
        $callable = [$controller, $action];

        return $callable(...$args);
    }
}
