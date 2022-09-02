<?php

namespace Zedium\Classes;

class Dashboard
{
    public function __construct(){
        add_action('wp_dashboard_setup', [$this, 'initDashboardSetup']);
        $this->refreshButtonCallback();
    }

    function initDashboardSetup(){
        wp_add_dashboard_widget(
            'zedium-coin-dashboard',
            __('Coin Prices', ZEDIUM_TEXT_DOMAIN),
            [$this, 'zedium_dashboard_widget_render_callback']
        );
    }

    public function zedium_dashboard_widget_render_callback(){
        ?>
        <form method="post">
            <input type="hidden" name="zedium_refresh_widget" value="refresh"/>
            <input type="submit" name="submit" value="Refresh" class="button"/>
        </form>
        <?php
        $data_table = new DataTable();
        $data_table->prepare_items();
        $data_table->display();

    }


    public function refreshButtonCallback(){
        if( 'POST' == strtoupper($_SERVER['REQUEST_METHOD'] )){

            if(isset($_POST['zedium_refresh_widget'])){
                (new Cronjob())->cronHookCallback();
            }

        }

    }

}