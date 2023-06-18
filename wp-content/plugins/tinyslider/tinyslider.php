<?php
/**
 * @package tinyslider
 */
/*
Plugin Name: tinyslider
Plugin URI: https://tinyslider.com/
Description: tinyslider plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: tinyslider
*/
function tinys_plugins_loaded() {
    load_plugin_textdomain('tinyslider', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'tinys_plugins_loaded');

/**
 * Add custom image size
 */
function tslide_image_size() {
    add_image_size('tslide_size', 800, 800, true);
}
 add_action('init', "tslide_image_size");
/**
 * Shortcode for tslider
 */
function tslide_slider_func($attributes, $content) {
    $defaults = array(
        'width' => 800,
        'height' => 800
    );
    $default_atts = shortcode_atts($defaults, $attributes);
    $content = do_shortcode($content);

    $output = <<<EOD
    <div style="width:{$default_atts['width']}px; height: {$default_atts['height']}px;" >
        <div class="slider">
            {$content}
        </div>
    </div>
    EOD;
    return $output;
}
add_shortcode('tslider', 'tslide_slider_func');


/**
 * Shortcode for tslide
 */
function tslide_generate_slide_func($attributes, $content) {
    $defaults = array(
        "id" => "",
        "caption" => "",
        "size"=> "tslide_size"
    );
    $default_attributes = shortcode_atts( $defaults, $attributes );
    $image_src = wp_get_attachment_image_src($default_attributes['id'],$default_attributes["size"] );
    $output = <<<EOD
    <div class="slide">
        <img src={$image_src[0]} alt={$default_attributes['caption']} />
    </div>
    EOD;
    return $output;
}
add_shortcode("tslide", "tslide_generate_slide_func");


/***
 * Include require asset files
 */
function tslide_essential_assets() {
    wp_enqueue_style( 'tslide-css', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css', null, '1.0.0' );
    wp_enqueue_script('tslide-js', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js',array('jquery'), '1.0.0', true);
    wp_enqueue_script('main-js', plugin_dir_url(__FILE__). 'assets/js/main.js', array('jquery'), time(), true);
}
add_action("wp_enqueue_scripts", "tslide_essential_assets");