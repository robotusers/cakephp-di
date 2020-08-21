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
namespace TestApp;

use Cake\Console\CommandCollection;
use Cake\Http\MiddlewareQueue;
use Psr\Container\ContainerInterface;
use Robotusers\Di\Http\BaseApplication;
use TestApp\Shell\TestShell;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

class Application extends BaseApplication
{
    protected $container;

    public function __construct($configDir, ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct($configDir);
    }

    public function console(CommandCollection $commands): CommandCollection
    {
        return $commands->add('test', TestShell::class);
    }

    protected function createContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function middleware(MiddlewareQueue $middleware): MiddlewareQueue
    {
        return $middleware;
    }
}
