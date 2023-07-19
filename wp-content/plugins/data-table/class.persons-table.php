<?php
// first add class-wp-list-table.php
if ( ! class_exists( "WP_List_Table" ) ) {
	require_once( ABSPATH . "wp-admin/includes/class-wp-list-table.php" );
}

class Persons_Table extends WP_List_Table {
    // create temparory variable $_items for pagination
    private $_items;
    // construct first
    function __construct( $args = array()) {
        parent::__construct($args);
    }
    // set data
    function set_data($data) {
        // for pagination
        $this->_items = $data;
    }
    // set our custom columns
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'name' => 'Name',
            'email' => 'Email',
            'age' => 'Age',
            'sex' => 'Sex'
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
    function extra_tablenav($which) {
        if('top' == $which) :?>
        <div class="actions alignleft">
            <select name="filter_s" id="filter_s">
                <option value="all">All</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
            <?php submit_button('Filter', 'button', 'submits', false); ?>
        </div>
    <?php endif;
    }
    function prepare_items() {
        // get columns
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        /**
         * Create a pagination
         */
        $total_page = count($this->_items);
        $per_page = 6;
        $paged = $_REQUEST['paged'] ?? 1;
        $data_chunks = array_chunk($this->_items, $per_page);
        $this->items = $data_chunks[$paged - 1];
        $this->set_pagination_args([
            'total_items' => $total_page,
            'per_page' => $per_page,
            'total_page' => ceil($total_page / $per_page)
        ]);
    }
    // for insert data into table ( item is each row)
    function column_default($item, $column_name) {
        return $item[$column_name];
    }
}