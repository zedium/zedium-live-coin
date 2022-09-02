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


    /*
     * Check for API key, if not exists show notice if admin dashboard
     */
    if( !file_exists(dirname(__FILE__) . '/api.key')){

        add_action( 'admin_notices', 'zedium_api_admin_notice_warn' );

    }




    /*
     * Call back for admin notice
     */
    function zedium_api_admin_notice_warn() {

        echo '<div class="notice notice-warning is-dismissible">
              <p><h3>Zedium Live Coin</h3> <strong>api.key </strong> not found,
               please signup in <strong>Coin Market Cap</strong> website and get <strong>API Token</strong>.
               Then add it in <strong>api.key</strong> file
              </p></div>';

    }





    /*
     * PSR-4 autoload
     */
    require_once __DIR__ . '/vendor/autoload.php';




    /*
     * Config file
     */
    require_once __DIR__ . '/config.php';




    /*
     * Custom DB is a class to interact with database and custom table
     */
    $database = CustomDB::getInstance();




    /*
     * Initialize Post type
     * Inject Custom Metabox to Post Type class.
     */
    new PostType(new MetaBoxes($database));




    /*
     * This hook will be called when plugin activates
     * It checks if there is no table named `coin_info` create it.
     */
    register_activation_hook(__FILE__, 'zedium_register_activation_hook_callback');

    function zedium_register_activation_hook_callback() {

        $database = CustomDB::getInstance();

        $database->createDatabase();

    }




    /*
     * Instantiate object
     */
    new Dashboard();
    new Cronjob();
    new AdminMenu();