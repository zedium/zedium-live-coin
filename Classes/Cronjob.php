<?php

namespace Zedium\Classes;

class Cronjob
{
    public function __construct(){
        add_filter( 'cron_schedules', [$this,'setInterval'] );
        add_action( 'bl_zedium_cron_hook', [$this,'cronHookCallback'] );
        register_deactivation_hook( __FILE__, [$this, 'deactivateHook'] );
        $this->initSchedule();
    }

    public function initSchedule(){
        if ( ! wp_next_scheduled( 'bl_zedium_cron_hook' ) ) {
            wp_schedule_event( time(), 'ten_minutes', 'bl_zedium_cron_hook' );
        }
    }

    function setInterval( $schedules ) {
        $schedules['ten_minutes'] = array(
            'interval' => CRON_INTERVAL,
            'display'  => esc_html__( 'Every 10 Minutes' ), );
        return $schedules;
    }
    function deactivateHook(){

        $timestamp = wp_next_scheduled( 'bl_zedium_cron_hook' );
        wp_unschedule_event( $timestamp, 'bl_zedium_cron_hook' );

    }
    function cronHookCallback(){

        $database = CustomDB::getInstance();
        $remote_call = new RemoteCall();
        $results = $remote_call->doRequest();
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