<?php

namespace Zedium\Classes;

class Dashboard
{
    public function __construct(){
        add_action('wp_dashboard_setup', [$this, 'initDashboardSetup']);
    }

    function initDashboardSetup(){
        wp_add_dashboard_widget(
            'zedium-coin-dashboard',
            __('Coin Prices', ZEDIUM_TEXT_DOMAIN),
            [$this, 'zedium_dashboard_widget_render_callback']
        );
    }

    public function zedium_dashboard_widget_render_callback(){
        $data_table = new DataTable();
//        var_dump($data_table->get_table_data());
//        die();
        $data_table->prepare_items();
        $data_table->display();
    }
}