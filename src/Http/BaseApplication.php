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

namespace Robotusers\Di\Http;

use Cake\Http\BaseApplication as CakeApplication;
use Cake\ORM\TableRegistry;
use Robotusers\Di\ORM\Locator\ContainerFactory;
use Robotusers\Di\ORM\Locator\TableLocator;

/**
 * @author Robert Pustułka <robert.pustulka@gmail.com>
 */

abstract class BaseApplication extends CakeApplication
{

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        parent::bootstrap();
        $tableLocator = $this->createTableLocator();
        TableRegistry::setTableLocator($tableLocator);
    }

    /**
     * This methods creates a default table locator that leverages app's DIC.
     *
     * @return \Robotusers\Di\ORM\Locator\TableLocator
     */
    protected function createTableLocator(): TableLocator
    {
        $factory = new ContainerFactory($this->getContainer());

        return new TableLocator($factory);
    }
}
