<?php
/*
 * The MIT License
 *
 * Copyright 2017 Robert Pustułka <r.pustulka@robotusers.com>.
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
use Cake\Http\ControllerFactory as BaseControllerFactory;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Mailer\Exception\MissingActionException;
use LogicException;
use Psr\Container\ContainerInterface;
use ReflectionMethod;
use ReflectionParameter;

/**
 * ControllerFactory
 *
 * @author Robert Pustułka <r.pustulka@robotusers.com>
 */
class ControllerFactory extends BaseControllerFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function create(ServerRequest $request, Response $response)
    {
        $className = $this->getControllerClass($request);
        if (!$className) {
            $this->missingController($request);
        }

        /* @var $controller Controller */
        $controller = $this->container->get($className);
        $controller->setRequest($request);
        $controller->response = $response;

        return $controller;
    }

    /**
     * Dispatches the controller action. Checks that the action
     * exists and isn't private.
     *
     * @return mixed The resulting response.
     * @throws \LogicException When request is not set.
     * @throws \Cake\Controller\Exception\MissingActionException When actions are not defined or inaccessible.
     */
    public function invokeAction(Controller $controller)
    {
        $request = $controller->request;
        if (!isset($request)) {
            throw new LogicException('No Request object configured. Cannot invoke action');
        }
        if (!$controller->isAction($request->getParam('action'))) {
            throw new MissingActionException([
                'controller' => $controller->name . 'Controller',
                'action' => $request->getParam('action'),
                'prefix' => $request->getParam('prefix') ?: '',
                'plugin' => $request->getParam('plugin'),
            ]);
        }

        $action = $request->getParam('action');

        $reflector = new ReflectionMethod($this, $action);

        /* @var $parameters ReflectionParameter[] */
        $parameters = $reflector->getParameters();

        $args = array_values($request->getParam('pass'));
        foreach ($parameters as $parameter) {
            $class = $parameter->getClass();
            if ($class) {
                $id = $class->getName();
            } else {
                $id = $parameter->getName();
            }
            if ($this->container->has($id)) {
                $args = $this->container->get($id);
            }
        }

        /* @var callable $callable */
        $callable = [$controller, $action];

        return $callable(...$args);
    }
}
