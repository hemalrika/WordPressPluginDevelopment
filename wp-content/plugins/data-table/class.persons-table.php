<?php
// first add class-wp-list-table.php
if ( ! class_exists( "WP_List_Table" ) ) {
	require_once( ABSPATH . "wp-admin/includes/class-wp-list-table.php" );
}

class Persons_Table extends WP_List_Table {
    // construct first
    function __construct( $args = array()) {
        parent::__construct($args);
    }
    // set data
    function set_data($data) {
        $this->items = $data;
    }
    // set our custom columns
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'name' => 'Name',
            'email' => 'Email',
            'age' => 'Age'
        ];
    }
    /**
     * For sortable columns
     */
    function get_sortable_columns() {
        return ['age' => [
                'age', true
            ]
        ];
    }
    function column_cb($item) {
        return "<input type='checkbox' value={$item['id']} />";
    }
    function column_email($item) {
        return "<strong>{$item['email']}</strong>";
    }
    function column_age($item) {
        return "<strong>{$item['age']}</strong>";
    }
    function prepare_items() {
        // get columns
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
    }
    // for insert data into table ( item is each row)
    function column_default($item, $column_name) {
        return $item[$column_name];
    }
}