<?php

namespace Zedium\Classes;

class CustomDB
{
    function __construct(){

        if( false == $this->checkTableExists() )
            $this->createTable();

    }

    private function checkTableExists(): bool{

        global $wpdb;

        if( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->base_prefix}".ZEDIUM_TABLE_NAME ."'")
                != $wpdb->base_prefix.ZEDIUM_TABLE_NAME )
            return false;

        return true;

    }

    private function createTable(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `{$wpdb->base_prefix}".ZEDIUM_TABLE_NAME."` (
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
}