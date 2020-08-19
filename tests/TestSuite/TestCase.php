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
namespace Robotusers\Di\Test\TestSuite;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase as BaseTestCase;
use TestApp\Application;
use const DS;
use const PLUGIN_ROOT;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        Configure::write('App.namespace', 'TestApp');
    }

    protected function getApplication($container)
    {
        return new Application(PLUGIN_ROOT . DS . 'tests' . DS . 'test_app', $container);
    }
}
