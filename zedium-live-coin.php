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
    use Zedium\Classes\Dashboard;
    use Zedium\Classes\RemoteCall;
    use Zedium\Classes\Cronjob;
    use Zedium\Classes\AdminMenu;

    if( !file_exists(__DIR__ . '/vendor/autoload.php') )
        die('autoload.php not found');

    if( !file_exists(dirname(__FILE__) . '/api.key')){

        add_action( 'admin_notices', 'zedium_api_admin_notice_warn' );

    }

    function zedium_api_admin_notice_warn() {
        echo '<div class="notice notice-warning is-dismissible">
              <p><h3>Zedium Live Coin</h3> <strong>api.key </strong> not found,
               please signup in <strong>Coin Market Cap</strong> website and get <strong>API Token</strong>.
               Then add it in <strong>api.key</strong> file
              </p></div>';
    }


    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/config.php';



    $database = CustomDB::getInstance();

    new PostType(new MetaBoxes($database));

    register_activation_hook(__FILE__, 'zedium_register_activation_hook_callback');

    function zedium_register_activation_hook_callback() {

        $database = CustomDB::getInstance();

        $database->createDatabase();

    }

    new Dashboard();
    new Cronjob();
    new AdminMenu();