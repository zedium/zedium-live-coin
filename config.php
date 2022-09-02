<?php

/*
 * These configs will be available in whole project
 */

const ZEDIUM_TEXT_DOMAIN = 'zedium-text-domain';
const ZEDIUM_POST_TYPE_NAME = 'coin';
const ZEDIUM_TABLE_NAME = 'coin_info';
const DATE_TIME_FORMAT = 'Y-m-d H:m:s';
const ZEDIUM_API_END_POINT = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest';
const CRON_INTERVAL = 600; // 10 Minutes

if( file_exists(dirname(__FILE__) . '/api.key'))
    define('COIN_MARKET_CAP_API_TOKEN', file_get_contents(dirname(__FILE__) . '/api.key'));
else
    define('COIN_MARKET_CAP_API_TOKEN','XXX');