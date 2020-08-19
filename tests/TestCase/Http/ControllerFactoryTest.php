<?php
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

use Cake\Http\Response;
use Cake\Http\ServerRequestFactory;
use Cake\ORM\Locator\LocatorInterface;
use Psr\Container\ContainerInterface;
use Robotusers\Di\Http\ControllerFactory;
use Robotusers\Di\Test\TestSuite\TestCase;
use TestApp\Controller\ArticlesController;
/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */
class ControllerFactoryTest extends \Robotusers\Di\Test\TestSuite\TestCase
{
    public function testCreate()
    {
        $controller = $this->createMock(\TestApp\Controller\ArticlesController::class);
        $container = $this->createMock(\Psr\Container\ContainerInterface::class);
        $container->expects($this->once())->method('get')->with(\TestApp\Controller\ArticlesController::class)->willReturn($controller);
        $request = \Cake\Http\ServerRequestFactory::fromGlobals()->withParam('controller', 'Articles');
        $response = new \Cake\Http\Response();
        $factory = new \Robotusers\Di\Http\ControllerFactory($container);
        $result = $factory->create($request, $response);
        $this->assertSame($controller, $result);
    }
    public function testInvoke()
    {
        $request = \Cake\Http\ServerRequestFactory::fromGlobals()->withParam('controller', 'Articles')->withParam('action', 'view')->withParam('pass', [1]);
        $response = new \Cake\Http\Response();
        $controller = new \TestApp\Controller\ArticlesController($request, $response);
        $container = $this->createMock(\Psr\Container\ContainerInterface::class);
        $locator = $this->createMock(\Cake\ORM\Locator\LocatorInterface::class);
        $container->expects($this->at(0))->method('has')->with(\Cake\ORM\Locator\LocatorInterface::class)->willReturn(true);
        $container->expects($this->at(1))->method('get')->with(\Cake\ORM\Locator\LocatorInterface::class)->willReturn($locator);
        $factory = new \Robotusers\Di\Http\ControllerFactory($container);
        $factory->invokeAction($controller);
    }
}
