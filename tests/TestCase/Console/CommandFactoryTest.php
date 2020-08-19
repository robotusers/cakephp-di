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
namespace Robotusers\Di\Test\TestCase\Console;

use Cake\Console\Command;
use Cake\TestSuite\TestCase;
use Psr\Container\ContainerInterface;
use Robotusers\Di\Console\CommandFactory;
use Robotusers\Di\Core\ContainerApplicationInterface;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

class CommandFactoryTest extends TestCase
{
    public function testCreate()
    {
        $command = $this->createMock(Command::class);
        $name = get_class($command);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())->method('get')->with($name)->willReturn($command);
        $app = $this->createMock(ContainerApplicationInterface::class);
        $app->expects($this->once())->method('getContainer')->willReturn($container);
        $factory = new CommandFactory($app);
        $result = $factory->create($name);
        $this->assertSame($command, $result);
    }
}
