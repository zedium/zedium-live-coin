<?php
    /*
     * Plugin name: Zedium Live Coin Price
     * Author: Zed
     * Plugin URI: https://github.com/zedium/zedium-live-coin
     * Author URI: https://github.com/zedium/
     */
    use Zedium\Classes\PostType;
    use Zedium\Classes\CustomDB;
    use Zedium\Classes\MetaBoxes;

    if( !file_exists(__DIR__ . '/vendor/autoload.php') )
        die('autoload.php not found');


    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/config.php';


    $database = CustomDB::getInstance();


    new PostType(new MetaBoxes($database));


    register_activation_hook(__FILE__, 'zedium_register_activation_hook_callback');


    function zedium_register_activation_hook_callback() {

        $database = CustomDB::getInstance();

        $database->createDatabase();

    }

