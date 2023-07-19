<?php
/**
 * @package data_table
 */
/*
Plugin Name: Data table
Plugin URI: https://ptc.com/
Description: Data table plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: data_table
*/
require_once "class.persons-table.php";
function datatable_admin_menu() {
    // create a menu on admin page
    add_menu_page("Data Table", "Data Table", "manage_options", "datatable", "datatable_display_func");
}
add_action("admin_menu", "datatable_admin_menu");

// this function will work for each row of data
function datatable_search_by_name($item) {
    $name        = strtolower( $item['name'] );
	$search_name = sanitize_text_field( $_REQUEST['s'] );
	if ( strpos( $name, $search_name ) !== false ) {
		return true;
	}

	return false;
}
function datatable_display_func() {
    include_once "dataset.php";
    // create table  using Persons_Table class
    $table = new Persons_Table();
    // if search submit, then apply filter for each row
    if ( isset( $_REQUEST['s'] ) && !empty($_REQUEST['s']) ) {
        $data = array_filter( $data, 'datatable_search_by_name' );
	}
    /**
     * Order functionality
     */
    $orderby = $_REQUEST['orderby'] ?? '';
    $order = $_REQUEST['order'] ?? '';
    if('age' == $orderby) {
        if('asc' == $order) {
            usort($data, function($item1, $item2) {
                return $item2['age'] <=> $item1['age'];
            });
        } else {
            usort($data, function($item1, $item2) {
                return $item1['age'] <=> $item2['age'];
            });
        }
    }
    // insert data into table
    $table->set_data($data);

    // now prepare all items
    $table->prepare_items();
    ?>
    <div class="wrap">
        <h2>Persons</h2>
        <form method="GET">
            <?php
            // for search form into table
            $table->search_box('search', 'search_id');
            // for display table
            $table->display();
            ?>
            <!-- this input used for prevent page redirect on submit -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
        </form>
    </div>
    <?php 
}