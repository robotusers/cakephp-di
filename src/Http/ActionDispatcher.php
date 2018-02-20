<?php
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
use Cake\Http\ActionDispatcher as BaseActionDispatcher;
use Cake\Http\Response;
use Cake\Routing\DispatcherFactory;
use LogicException;
use Robotusers\Di\Core\ContainerApplicationInterface;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */
class ActionDispatcher extends BaseActionDispatcher
{

    /**
     * {@inheritDoc}
     */
    protected function _invoke(Controller $controller)
    {
        $this->dispatchEvent('Dispatcher.invokeController', ['controller' => $controller]);

        $result = $controller->startupProcess();
        if ($result instanceof Response) {
            return $result;
        }

        if ($this->factory instanceof ControllerFactory) {
            $response = $this->factory->invokeAction($controller);
        } else {
            $response = $controller->invokeAction();
        }
        if ($response !== null && !($response instanceof Response)) {
            throw new LogicException('Controller actions can only return Cake\Http\Response or null.');
        }

        if (!$response && $controller->autoRender) {
            $controller->render();
        }

        $result = $controller->shutdownProcess();
        if ($result instanceof Response) {
            return $result;
        }
        if (!$response) {
            $response = $controller->response;
        }

        return $response;
    }

    /**
     * Creates a action dispatcher instance.
     *
     * @param ContainerApplicationInterface $application Application
     * @return ActionDispatcher
     */
    public static function create(ContainerApplicationInterface $application)
    {
        $filters = DispatcherFactory::filters();

        $container = $application->getContainer();
        $factory = new ControllerFactory($container);
        $dispatcher = new self($factory, null, $filters);

        return $dispatcher;
    }
}
