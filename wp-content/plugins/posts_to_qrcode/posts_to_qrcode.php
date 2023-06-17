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



function ptc_admin_init() {
    add_settings_field('pqrc_height', __("Height"), 'pqrc_display_height', 'general');
    add_settings_field('pqrc_width', __("Width"), 'pqrc_display_width', 'general');

    register_setting('general', 'pqrc_height', array('sanitize_callback' => 'esc_attr'));
    register_setting('general', 'pqrc_width', array('sanitize_callback' => 'esc_attr'));
}

function pqrc_display_height() {
    $height = get_option("pqrc_height");
    printf("<input type='text' value='%s' name='%s' id='%s' />", $height, 'pqrc_height', 'pqrc_height');
}
function pqrc_display_width() {
    $width = get_option("pqrc_width");
    printf("<input type='text' value='%s' name='%s' id='%s' />", $width, 'pqrc_width', 'pqrc_width');
}
add_action("admin_init", "ptc_admin_init");