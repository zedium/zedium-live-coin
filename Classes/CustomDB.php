<?php

namespace Zedium\Classes;

class CustomDB
{
    private static $instance = null;
    public $hello = 'Hi';
    public function __construct(){

    }
    public static function getInstance()
    {


        if ( !self::$instance ) {
            self::$instance = new CustomDB();
        }
        return self::$instance;

    }

    public function createDatabase()
    {

        if (false == $this->checkTableExists())
            $this->createTable();

    }

    private function checkTableExists(): bool
    {

        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->base_prefix}" . ZEDIUM_TABLE_NAME . "'")
            != $wpdb->base_prefix . ZEDIUM_TABLE_NAME)
            return false;

        return true;

    }

    private function createTable()
    {

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `{$wpdb->base_prefix}" . ZEDIUM_TABLE_NAME . "` (
                  id INT NOT NULL AUTO_INCREMENT,
                  post_id INT NOT NULL,
                  short_name varchar(50),
                  usd_price varchar(20) NOT NULL,
                  market_cap bigint(20) NOT NULL,
                  last_update datetime NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

    }

    public function isPostMetaExists($postID)
    {

        global $wpdb;

        $sql_query = $wpdb->prepare(
            'SELECT id,post_id FROM ' .
            $wpdb->base_prefix . ZEDIUM_TABLE_NAME .
            " WHERE post_id = '%d'",
            $postID);

        return !empty($wpdb->get_row($sql_query));
    }

    public function getMetaBoxByPostID($postID){
        global $wpdb;

        $sql_query = $wpdb->prepare(
            'SELECT * FROM ' .
            $wpdb->base_prefix . ZEDIUM_TABLE_NAME .
            " WHERE post_id = '%d'",
            $postID);

        return $wpdb->get_row($sql_query);
    }

    public function insertPostMeta($post_id, $short_name, $usd_price, $market_cap, $last_update )
    {
        global $wpdb;

        $sql_query = $wpdb->prepare(
            'INSERT INTO ' .
            $wpdb->base_prefix . ZEDIUM_TABLE_NAME .
            ' (`post_id`, `short_name`, `usd_price`, `market_cap`, `last_update` ) ' .
            ' VALUES (%d, %s, %d, %d, %s)', [$post_id, $short_name, $usd_price, $market_cap, $last_update]
        );

        $wpdb->query($sql_query);
    }

    public function updatePostMeta($post_id, $short_name, $usd_price, $market_cap, $last_update )
    {
        global $wpdb;

        $sql_query = $wpdb->prepare(
            'UPDATE  `' .
            $wpdb->base_prefix . ZEDIUM_TABLE_NAME .
            "` SET `short_name`='%s', `usd_price`='%d', `market_cap`='%d', `last_update`='%s' 
            WHERE `post_id`='%s'" ,
            [$short_name, $usd_price, $market_cap, $last_update, $post_id]
        );

        $wpdb->query($sql_query);
    }


    public function dropTable()
    {
        global $wpdb;

        $sql_query = 'DROP TABLE ' . $wpdb->base_prefix . ZEDIUM_TABLE_NAME . ';';

        $wpdb->query($sql_query);

    }

}