<?php

namespace Zedium\Classes;

/**
 * This class is responsible for generate list of coins that called Datatable
 * In both home page dashboard and custom menu page
 */

class DataTable extends \WP_List_Table
{
    /**
     * @var Data from database table
     */
    private $table_data;

    public function __construct($args = array())
    {
        parent::__construct($args);
    }

    public function get_columns()
    {
        $columns = array(

            'short_name'=> 'Name',
            'usd_price'=>'Price',
            'market_cap'=>'MC',
            'last_update'=>'Updated'

        );

        return $columns;
    }

    function prepare_items()
    {
        $columns = $this->get_columns();

        /*
         * Get data from database table
         */
        $this->table_data = $this->get_table_data();
        $hidden = array();
        $sortable = array(); // We don't have sortable items
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $this->table_data;

    }

    public function get_table_data() {

        $database = CustomDB::getInstance();

        return $database->getCoinList();

    }

    function column_default($item, $column_name)
    {

        switch ($column_name) {

            case 'short_name':
            case 'usd_price':
            case 'market_cap':
            case 'last_update':

            default:
                return $item[$column_name];
        }

    }
    // We don't have and select checkbox
    function column_cb($item)
    {
/*        return sprintf(
            '<input type="checkbox" name="element[]" value="%s" />',
            $item['id']
        );*/

    }

}