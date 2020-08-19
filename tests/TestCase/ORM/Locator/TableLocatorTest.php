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
namespace Robotusers\Di\Test\TestCase\ORM\Locator;

use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use Robotusers\Di\ORM\Locator\TableLocator;
/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */
class TableLocatorTest extends \Cake\TestSuite\TestCase
{
    public function testGet()
    {
        $table = new \Cake\ORM\Table();
        $called = false;
        $factory = function ($options) use (&$called, &$table) {
            $called = true;
            $this->assertEquals('Authors', $options['alias']);
            return $table;
        };
        $locator = new \Robotusers\Di\ORM\Locator\TableLocator($factory);
        $result = $locator->get('Authors');
        $this->assertTrue($called);
        $this->assertSame($table, $result);
    }
}
