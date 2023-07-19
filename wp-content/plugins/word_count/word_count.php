<?php
/**
 * @package word_count
 */
/*
Plugin Name: WordCount
Plugin URI: https://WordCount.com/
Description: WordCount plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: word_count
*/
function wordcount_plugins_loaded() {
    load_plugin_textdomain('wordcount', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'wordcount_plugins_loaded');

function wordcount_count_words($content) {
    $stripedContent = strip_tags($content);
    $countContent = strlen($stripedContent);
    $label = apply_filters("wordcount_label", "Total Word Count: ");
    $tag = apply_filters("wordcount_tag", "h2");
    $content .= sprintf("<%s>%s %s</%s>", $tag, $label, $countContent, $tag);
    return $content;

}
add_filter("the_content", "wordcount_count_words");





