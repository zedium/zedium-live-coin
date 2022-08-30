<?php
/*
 * Plugin name: Zedium Live Coin Price
 * Author: Zed
 * Plugin URI: https://github.com/zedium/zedium-live-coin
 * Author URI: https://github.com/zedium/
 */
use Zedium\Classes\PostType;

if( !file_exists(__DIR__ . '/vendor/autoload.php') )
    die('autoload.php not found');

const ZEDIUM_TEXT_DOMAIN = 'zedium-text-domain';

require_once __DIR__ . '/vendor/autoload.php';

new PostType();
