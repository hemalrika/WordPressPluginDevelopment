<?php
/**
 * @package ptq
 */
/*
Plugin Name: Posts To QR Code
Plugin URI: https://ptc.com/
Description: posts to qr code plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: ptq
*/
function ptc_plugins_loaded() {
    load_plugin_textdomain('ptc', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'ptc_plugins_loaded');

function ptc_insert_qr_code_into_content($content) {
    $page_id = get_the_ID();
    $current_post_type = get_post_format($page_id);
    $excluded_post_type = apply_filters("ptc_excluded_post_format_for_qr_code", array());
    if(in_array($current_post_type, $excluded_post_type)) {
        return $content;
    }
$height = get_option('pqrc_height');
$width = get_option('pqrc_width');
$height = $height ? $height: 150;
$width = $width ? $width: 150;
$qr_code_dimension = apply_filters("atc_qr_code_dimension", "{$height}x{$width}");
    $title = get_the_title($page_id);
    $page_permalink = urlencode(get_the_permalink($page_id));
    $qrcode_api = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s',$qr_code_dimension,  $page_permalink);
    $content .= sprintf("<div class='qr-code-img'><img src='%s' alt='%s' /></div>", $qrcode_api, $title);
    return $content;
}
add_filter("the_content", "ptc_insert_qr_code_into_content");

$pqrc_countries = array(
    'Bangladesh',
    'Africa',
    'Malaysia',
    'Maldives',
    'New York',
    'Netherland',
    'Australia'
);
function pqrc_init() {
    global $pqrc_countries;
    $pqrc_countries = apply_filters("pqrc_country_list", $pqrc_countries);
}
add_action('init', 'pqrc_init');

function ptc_admin_init() {
    add_settings_section('pqrc_section', __('QR Code Scanner', 'ptc'), 'pqrc_callback', 'general');

    add_settings_field('pqrc_height', __("Height"), 'pqrc_display_func', 'general', 'pqrc_section', array('pqrc_height'));
    add_settings_field('pqrc_width', __("Width"), 'pqrc_display_func', 'general', 'pqrc_section', array('pqrc_width'));

    register_setting('general', 'pqrc_height', array('sanitize_callback' => 'esc_attr'));
    register_setting('general', 'pqrc_width', array('sanitize_callback' => 'esc_attr'));
}
add_action("admin_init", "ptc_admin_init");
function pqrc_callback() {
    echo '<p>This is a post to qrcode scanner Description</p>';
}
function pqrc_display_func($args) {
    $height = get_option($args[0]);
    printf("<input type='text' value='%s' name='%s' id='%s' />", $height, $args[0], $args[0]);
}


/***
 * Create select into settings panel
 */

function ptc_create_country_settings() {
    add_settings_field('pqrc_country', 'Select Country', 'pqrc_country_callback', 'general');
    register_setting('general', 'pqrc_country', array('senitize_callback'=> 'esc_attr'));
}
add_action('admin_init', 'ptc_create_country_settings');

function pqrc_country_callback() {
    $selected_country = get_option('pqrc_country');
    global $pqrc_countries;
    printf("<select id='%s' name='%s'>", 'pqrc_country', 'pqrc_country');
    foreach($pqrc_countries as $country) {
        $selected_country_selected = '';
        if($selected_country == $country) {
                $selected_country_selected = 'selected';
            }
            printf("<option value='%s' %s>%s</option>", $country, $selected_country_selected, $country);
        }
    echo '</select>';
}



/**
 * Create multiple select checkbox
 */

function ptc_multiple_select_checkbox() {
    add_settings_field('multiple_select_country', 'Select Your Country', 'multiple_select_country_callback', 'general');
    register_setting( 'general', 'multiple_select_country' );
}
add_action('admin_init', 'ptc_multiple_select_checkbox');
function multiple_select_country_callback() {
    $options = get_option('multiple_select_country');
    global $pqrc_countries;
    foreach($pqrc_countries as $country) {
        $country_checked = '';
        if(is_array($options) && in_array($country, $options)) {
            $country_checked = 'checked';
        }
        printf("<input type='checkbox' name='multiple_select_country[]' value='%s' %s /> %s<br/>", $country, $country_checked, $country);
    }
}

/**
 * Add toggle setting field into generel
 */



/**
  * Load essential CSS and js files
*/
function pqrc_add_external_assets($screen) {
    echo $screen;
    if('options-general.php' == $screen) {
        wp_enqueue_style('minitoggle-css', plugin_dir_url(__FILE__).'assets/css/minitoggle.css', null, false);
        wp_enqueue_script('minitoggle-js', plugin_dir_url(__FILE__).'assets/js/minitoggle.js', array('jquery'),'1.0.0', true);
        wp_enqueue_script('main', plugin_dir_url(__FILE__).'assets/js/main.js', array('jquery'), time(), true);
    }
}
add_action("admin_enqueue_scripts", "pqrc_add_external_assets");



/**
 * Add settings field into admin bar
 */
function pqrc_switcher_setting_field_func() {
    add_settings_field('pqrc_switcher', 'Switcher Control', 'pqrc_switcher_display_callback', 'general');
    register_setting('general', 'pqrc_switcher');
}
add_action('admin_init', 'pqrc_switcher_setting_field_func');

/***
 * Display output
 */
function pqrc_switcher_display_callback() {
    $option = get_option('pqrc_switcher');
    echo '<div id="toggle1"></div>';
    echo '<input type="hidden" value="'.$option.'" name="pqrc_switcher" id="toggle_input" />';
}