<?php
/**
 * @package assetsninja
 */
/*
Plugin Name: assetsninja
Plugin URI: https://assetsninja.com/
Description: assetsninja plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: assetsninja
*/

define("ASN_ASSETS", plugin_dir_url(__FILE__). 'assets/');
define("ASN_PUBLIC_ASSETS", plugin_dir_url(__FILE__). 'assets/public/');
define("ASN_ADMIN_ASSETS", plugin_dir_url(__FILE__). 'assets/admin/');
define("ASN_VERSION", time());
class AssetsNinja {
    private $version;
    public function __construct() {
        $this->version = ASN_VERSION;
        add_action("plugins_loaded", array($this, "load_textdomain"));
        add_action("wp_enqueue_scripts", array($this, "load_front_assets"));
        add_action("admin_enqueue_scripts", array($this, "load_admin_assets"));
    }
    function load_textdomain() {
        load_plugin_textdomain( 'assetsninja', false, plugin_dir_url(__FILE__).'/languages' );
    }
    function load_front_assets() {
        $js_files = array(
            'asn-main-js' => array(
                'url' => ASN_PUBLIC_ASSETS.'js/main.js',
                'dependency' => array('jquery')
            )
        );
        wp_enqueue_style( 'asn-style-css', ASN_PUBLIC_ASSETS.'css/style.css', null, $this->version );
        foreach($js_files as $index=>$file) {
            wp_enqueue_script($index, $file['url'], $file['dependency'], $this->version, true);
        }

        $data = [
            'name' => 'MD hemal akhand',
            'email' => 'hemalrika@gmail.com'
        ];
        wp_localize_script('asn-main-js', 'maindata', $data);
    }
    function load_admin_assets($screen) {
        $_screen = get_current_screen();
        if('edit.php' == $screen && 'page' == $_screen->post_type) {
            wp_enqueue_script('admin-js', ASN_ADMIN_ASSETS. 'js/admin.js', array('jquery'), $this->version, true);
        }
    }
}
new AssetsNinja();