<?php
use Zedium\Classes\CustomDB;

/*
 * This file will be called when plugin is uninstalling.
 * It will drop table and purge the custom `coin_info` data
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}


if( !file_exists(__DIR__ . '/vendor/autoload.php') )
    die('autoload.php not found');


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
$database = CustomDB::getInstance();

$database->dropTable();

