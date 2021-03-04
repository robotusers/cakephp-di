<?php
declare(strict_types=1);

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
namespace Robotusers\Di\ORM\Locator;

use Cake\ORM\Locator\TableLocator as BaseTableLocator;
use Cake\ORM\Table;

/**
 * Table locator with support for a factory.
 *
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

class TableLocator extends BaseTableLocator
{
    /**
     * @var callable
     */
    protected $factory;

    /**
     * Constructor
     *
     * @param callable $factory Table factory.
     * @param array<int, mixed> $args Rest of the arguments
     */
    public function __construct(callable $factory, ...$args)
    {
        $this->factory = $factory;

        parent::__construct(...$args);
    }

    /**
     * @inheritDoc
     * 
     * @param array<string, mixed> $options
     * @return Table
     */
    protected function _create(array $options): Table
    {
        $factory = $this->factory;

        return $factory($options);
    }
}
