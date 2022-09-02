<?php

namespace Zedium\Classes;

class Cronjob
{
    public function __construct(){

        //filter for set interval to cron
        add_filter( 'cron_schedules', [$this,'setInterval'] );


        //callback for cron ticks
        add_action( 'bl_zedium_cron_hook', [$this,'cronHookCallback'] );


        // remove cron schedule when plugin is disabled
        register_deactivation_hook( __FILE__, [$this, 'deactivateHook'] );


        //schedule the cron for ten minutes
        $this->initSchedule();

    }

    /**
     * Schedule the cron for 10 minutes
     * @return void
     */
    public function initSchedule(){

        if ( ! wp_next_scheduled( 'bl_zedium_cron_hook' ) ) {
            wp_schedule_event( time(), 'ten_minutes', 'bl_zedium_cron_hook' );
        }

    }


    /**
     * Filter for alter cron tick interval
     * @param $schedules
     * @return mixed
     */
    function setInterval($schedules) {
        $schedules['ten_minutes'] = array(
            'interval' => CRON_INTERVAL,
            'display'  => esc_html__( 'Every 10 Minutes' ), );
        return $schedules;
    }


    /**
     *
     * Unschedule cron job when plugin disabled
     * @return void
     */
    function deactivateHook(){

        $timestamp = wp_next_scheduled( 'bl_zedium_cron_hook' );
        wp_unschedule_event( $timestamp, 'bl_zedium_cron_hook' );

    }


    /**
     *
     * In every tick of cron this function will send a api call to remote endpoint
     * get the data
     * parse the data
     * iterate through the data and update the database table by new fetched data
     * @return void
     */
    function cronHookCallback(){

        $database = CustomDB::getInstance();
        $remote_call = new RemoteCall();
        $results = $remote_call->doRequest();
        if(empty($results))
            return;
        foreach ($results as $result ){
            $database->updatePostMetaByShortName(
                $result->short_name,
                $result->usd_price,
                $result->market_cap,
                $result->last_update
            );
        }
    }

}