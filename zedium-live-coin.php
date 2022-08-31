<?php
/*
 * Plugin name: Zedium Live Coin Price
 * Author: Zed
 * Plugin URI: https://github.com/zedium/zedium-live-coin
 * Author URI: https://github.com/zedium/
 */
use Zedium\Classes\PostType;
use Zedium\Classes\CustomDB;
if( !file_exists(__DIR__ . '/vendor/autoload.php') )
    die('autoload.php not found');


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

new PostType();


register_activation_hook(__FILE__, 'zedium_register_activation_hook_callback');

function zedium_register_activation_hook_callback(){

    new CustomDB();

}