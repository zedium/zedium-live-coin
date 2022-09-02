<?php
const ZEDIUM_TEXT_DOMAIN = 'zedium-text-domain';
const ZEDIUM_POST_TYPE_NAME = 'coin';
const ZEDIUM_TABLE_NAME = 'coin_info';
const DATE_TIME_FORMAT = 'Y-m-d H:m:s';
define('COIN_MARKET_CAP_API_TOKEN', file_get_contents(dirname(__FILE__) . '/api.key'));
const CRON_INTERVAL = 60;