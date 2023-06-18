<?php
/**
 * @package shortcode
 */
/*
Plugin Name: ShortCode
Plugin URI: https://ShortCode.com/
Description: ShortCode plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: shortcode
*/
function sortcode_plugins_loaded() {
    load_plugin_textdomain('sortcode', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'sortcode_plugins_loaded');

/**
 * Create a shortcode
 */
function shortcode_button_func($attributes, $content='') {
    $default = array(
        'url' => ''
    );
    $default_atts = shortcode_atts($default, $attributes);
    return sprintf('<a class="primary-btn" href="%s">%s</a>', $default_atts['url'], do_shortcode($content));
}
 add_shortcode('button2', 'shortcode_button_func');