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

use Cake\Http\ServerRequestFactory;
use Cake\ORM\Locator\LocatorInterface;
use Psr\Container\ContainerInterface;
use Robotusers\Di\Controller\ControllerFactory;
use Robotusers\Di\Test\TestSuite\TestCase;
use TestApp\Controller\ArticlesController;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

class ControllerFactoryTest extends TestCase
{
    public function testCreate()
    {
        $request = ServerRequestFactory::fromGlobals()->withParam('controller', 'Articles');

        $controller = $this->createMock(ArticlesController::class);
        $controller->expects($this->once())->method('setRequest')->with($request);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())->method('get')->with(ArticlesController::class)->willReturn($controller);

        $factory = new ControllerFactory($container);
        $result = $factory->create($request);
        $this->assertSame($controller, $result);
    }

    public function testInvoke()
    {
        $request = ServerRequestFactory::fromGlobals()->withParam('controller', 'Articles')->withParam('action', 'view')->withParam('pass', [1]);
        $controller = new ArticlesController($request);

        $container = $this->createMock(ContainerInterface::class);
        $locator = $this->createMock(LocatorInterface::class);
        $container->expects($this->at(0))->method('has')->with(LocatorInterface::class)->willReturn(true);
        $container->expects($this->at(1))->method('get')->with(LocatorInterface::class)->willReturn($locator);

        $factory = new ControllerFactory($container);
        $factory->invoke($controller);
    }
}
