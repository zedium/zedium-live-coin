<?php

namespace Zedium\Classes;

/**
 * This class is responsible for render home dashboard widget
 */
class Dashboard
{
    public function __construct(){

        add_action('wp_dashboard_setup', [$this, 'initDashboardSetup']);
        $this->refreshButtonCallback();

    }

    /**
     * Add widget to system widgets action
     * @return void
     */
    function initDashboardSetup(){

        wp_add_dashboard_widget(
            'zedium-coin-dashboard',
            __('Coin Prices', ZEDIUM_TEXT_DOMAIN),
            [$this, 'zedium_dashboard_widget_render_callback']
        );

    }

    /**
     * Render html
     *
     * Create form and submit button to be able to refresh and get latest data
     *
     * @return void
     */
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


    /**
     * 
     * If refresh submit button clicked then rerun cron manually to fetch new data from API
     *
     * @return void
     */
    public function refreshButtonCallback(){
        if( 'POST' == strtoupper($_SERVER['REQUEST_METHOD'] )){

            if(isset($_POST['zedium_refresh_widget'])){
                (new Cronjob())->cronHookCallback();
            }

        }

    }

}