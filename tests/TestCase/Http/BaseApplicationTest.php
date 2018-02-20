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

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Psr\Container\ContainerInterface;
use Robotusers\Di\ORM\Locator\TableLocator;
use TestApp\Application;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */
class BaseApplicationTest extends TestCase
{
    public function testContainer()
    {
        $container = $this->createMock(ContainerInterface::class);
        $app = $this->getApplication($container);

        $this->assertSame($container, $app->getContainer());
    }

    public function testLocator()
    {
        $container = $this->createMock(ContainerInterface::class);
        $app = $this->getApplication($container);
        $app->bootstrap();

        $locator = TableRegistry::getTableLocator();
        $this->assertInstanceOf(TableLocator::class, $locator);
    }

    protected function getApplication($container)
    {
        return new Application(PLUGIN_ROOT . DS . 'tests' . DS . 'test_app', $container);
    }
}
