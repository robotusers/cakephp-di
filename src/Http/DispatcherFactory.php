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

use Cake\Core\HttpApplicationInterface;
use Cake\Routing\DispatcherFactory as RoutingDispatcherFactory;
use Robotusers\Di\Core\ContainerApplicationInterface;

/**
 * Description of DispatcherFactory
 *
 * @author Robert Pustułka <r.pustulka@robotusers.com>
 */
class DispatcherFactory
{

    /**
     * @param HttpApplicationInterface $application Application
     * @return ActionDispatcher
     */
    public static function create($application)
    {
        $filters = RoutingDispatcherFactory::filters();

        if ($application instanceof ContainerApplicationInterface) {
            $container = $application->container();
            $factory = new ControllerFactory($container);
            $dispatcher = new ActionDispatcher($factory, null, $filters);

            return $dispatcher;
        }

        return new ActionDispatcher(null, null, $filters);
    }
}
