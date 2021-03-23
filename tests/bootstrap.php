<?php
declare(strict_types=1);

use Cake\Core\BasePlugin;
use Cake\Core\Plugin;

/**
 * Test suite bootstrap.
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */

error_reporting(E_ALL);

$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = \dirname($root);
        if (\is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);
    throw new Exception('Cannot find the root of the application, unable to run tests');
};
$root = $findRoot(__FILE__);
unset($findRoot);

\define('PLUGIN_ROOT', $root);
\chdir($root);

if (\file_exists($root . '/config/bootstrap.php')) {
    require $root . '/config/bootstrap.php';
}

require $root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

Plugin::getCollection()->add(new BasePlugin([
    'name' => 'Robotusers/Di',
    'path' => PLUGIN_ROOT . DS,
]));
