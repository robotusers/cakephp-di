<?php
declare(strict_types=1);

/*
 * The MIT License
 *
 * Copyright 2018 Robert Pustułka <robert.pustulka@gmail.com>.
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
namespace Robotusers\Di\Test\TestCase\Http;

use Cake\Http\ControllerFactory as ControllerFactory2;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Psr\Container\ContainerInterface;
use Robotusers\Di\Http\ActionDispatcher;
use Robotusers\Di\Http\ControllerFactory;
use Robotusers\Di\Test\TestSuite\TestCase;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

class ActionDispatcherTest extends TestCase
{
    public function testCreate()
    {
        $container = $this->createMock(ContainerInterface::class);
        $application = $this->getApplication($container);
        $dispatcher = ActionDispatcher::create($application);
        $this->assertInstanceOf(ActionDispatcher::class, $dispatcher);
    }

    public function testDispatch()
    {
        $controller = $this->createMock(\Cake\Controller\Controller::class);
        $factory = $this->createMock(ControllerFactory::class);
        $request = new ServerRequest();
        $response = new Response();
        $factory->expects($this->at(0))->method('create')->with($request, $response)->willReturn($controller);
        $factory->expects($this->at(1))->method('invokeAction')->with($controller)->willReturn($response);
        $dispatcher = new ActionDispatcher($factory);
        $result = $dispatcher->dispatch($request, $response);
        $this->assertInstanceOf(Response::class, $result);
    }

    public function testDispatchRegular()
    {
        $controller = $this->createMock(\Cake\Controller\Controller::class);
        $factory = $this->createMock(ControllerFactory2::class);
        $request = new ServerRequest();
        $response = new Response();
        $factory->expects($this->once())->method('create')->with($request, $response)->willReturn($controller);
        $controller->expects($this->once())->method('invokeAction')->willReturn($response);
        $dispatcher = new ActionDispatcher($factory);
        $result = $dispatcher->dispatch($request, $response);
        $this->assertInstanceOf(Response::class, $result);
    }
}
