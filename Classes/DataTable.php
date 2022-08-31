<?php

namespace Zedium\Classes;

class DataTable extends \WP_List_Table
{
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
        $this->table_data = $this->get_table_data();
        $hidden = array();
        $sortable = array();
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
            case 'id':
            case 'name':
            case 'description':
            case 'status':
            case 'order':
            default:
                return $item[$column_name];
        }
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="element[]" value="%s" />',
            $item['id']
        );
    }

}