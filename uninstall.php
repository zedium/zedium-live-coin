<?php
use Zedium\Classes\CustomDB;

if( !file_exists(__DIR__ . '/vendor/autoload.php') )
    die('autoload.php not found');


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
$database = CustomDB::getInstance();

$database->dropTable();

