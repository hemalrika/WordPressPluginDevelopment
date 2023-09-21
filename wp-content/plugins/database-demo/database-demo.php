<?php
/**
 * @package database_demo
 */
/*
Plugin Name: Database Demo
Plugin URI: https://ptc.com/
Description: Database Demo plugin is a light weight for count words.
Version: 1.3
Requires at least: 1.3
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: database_demo
*/
define("WPDEMO_DB_VERSION", "1.3");

function dbdemo_init() {
    // for deal with db we need first get $wpdb variable
    global $wpdb;
    // get wp table prefix
    $table_name = $wpdb->prefix. "persons";
    // sql query for create table and field
    $sql = "CREATE TABLE {$table_name} (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(250),
        email VARCHAR(250),
        PRIMARY KEY(id)
    )";
    require_once (ABSPATH. 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    // add custom option for manage version of our plugin
    add_option("dbdemo_db_version", WPDEMO_DB_VERSION);
    // if option is update then add extra field into table
    if(get_option('dbdemo_db_version') != WPDEMO_DB_VERSION) {
        $sql = "CREATE TABLE {$table_name} (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(250),
            email VARCHAR(250),
            age INT,
            PRIMARY KEY(id)
        )";
        require_once (ABSPATH. 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option('dbdemo_db_version', WPDEMO_DB_VERSION);
    }
}
register_activation_hook(__FILE__, 'dbdemo_init');



// drop db if version not match
function wpdb_plugins_loaded() {
    global $wpdb;
    $table_name = $wpdb->prefix. "persons";
    if(get_post_meta("dbdemo_db_version") != WPDEMO_DB_VERSION) {
        $sql = "ALTER TABLE {$table_name} DROP COLUMN age";
        $wpdb->query($sql); 
    }
    update_option("dbdemo_db_version", WPDEMO_DB_VERSION);
}
add_action("plugins_loaded", "wpdb_plugins_loaded");


// ২৪.৩ - প্লাগইন অ্যাকটিভেশনের সময় টেবিলে ডেটা ইনসার্ট করা এবং ডিঅ্যাকটিভেশনের সময় টেবিল ফ্লাশ করা
function dbdemo_load_data() {
    global $wpdb;
    $table_name = $wpdb->prefix. "persons";
    $wpdb->insert($table_name, [
        'name' => "John Doe",
        'email' => 'john@doe.com'
    ]);
    $wpdb->insert($table_name, [
        'name' => "Jane Doe",
        'email' => 'jane@doe.com'
    ]);
}
register_activation_hook( __FILE__, "dbdemo_load_data" );

function dbdemo_flush_data() {
    global $wpdb;
    $table_name = $wpdb->prefix. "persons";

    $query = "TRUNCATE TABLE {$table_name}";
    $wpdb->query($query);
}
register_deactivation_hook(__FILE__, 'dbdemo_flush_data');



// ২৪.৪ - টেবিল থেকে ডেটা নিয়ে ডিসপ্লে করা
add_action("admin_menu", function() {
    add_menu_page("DB Demo", "DB Demo", "manage_options", "dbdemo", "dbdemo_admin_page");
});
function dbdemo_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix. "persons";
    echo "<h2>DB Demo</h2>";
    $id = $_GET['pid'] ?? 0;
    $id = sanitize_key($id);
    if($id) {
        $result = $wpdb->get_row("SELECT * FROM {$table_name} WHERE id='{$id}'");
        if($result) {
            echo "Name: ". $result->name. "<br/>";
            echo "Email: ". $result->email. "<br/>";
        }
    }?>
        <form action="" method="POST">
            <?php wp_nonce_field('dbdemo', 'nonce'); ?>
            Name: <input type="text" name="name" id="name"><br/>
            Email: <input type="email" name="email" id="email"><br/>
            <?php submit_button("Add Record"); ?>
        </form>
        <?php
            if(isset($_POST['submit'])) {
                $nonce = sanitize_text_field($_POST['nonce']);
                if(wp_verify_nonce($nonce, 'dbdemo')) {
                    $name = sanitize_text_field($_POST['name']);
                    $email = sanitize_text_field($_POST['email']);
                    $wpdb->insert($table_name, ['name' => $name, 'email' => $email]);
                } else {
                    echo "You're not allowed";
                }
            }
        ?>
<?php }