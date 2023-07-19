<?php
/**
 * @package column_demo
 */
/*
Plugin Name: Column Demo
Plugin URI: https://ptc.com/
Description: Column Demo plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: column_demo
*/
function cd_plugins_loaded() {
    load_plugin_textdomain('column_demo', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'cd_plugins_loaded');


function cd_manage_posts_column($column) {
    unset($column['author']);
    unset($column['date']);
    $column['author'] = "Author";
    $column['date'] = "Dates";
    $column['id'] = "Post ID";
    return $column;
}
add_filter("manage_posts_columns", "cd_manage_posts_column");
function cd_manage_posts_custom_column($column, $post_id) {
    if('id' == $column) {
        echo $post_id;
    }
}
add_action("manage_posts_custom_column", "cd_manage_posts_custom_column", 10, 2);


/**
 * Add thumbnail Title
 */
function cd_add_post_thumbnail_column($column) {
    $column['thumbnail'] = "Thumbnail";
    return $column;
}
add_filter("manage_posts_columns", "cd_add_post_thumbnail_column");
add_filter("manage_pages_columns", "cd_add_post_thumbnail_column");


/**
 * Add thumbnail data
 */

function cd_add_thumbnail_data($column, $post_id) {
    if('thumbnail' == $column) {
        $thumbnail = get_the_post_thumbnail($post_id, array(100, 100));
        echo $thumbnail;
    }
}
add_action("manage_posts_custom_column", "cd_add_thumbnail_data", 10, 2);



/**
 * Add a column named WordCount
 */
function coldemo_post_columns( $columns ) {
	$columns['wordcount'] = __( 'Word Count', 'column-demo' );
	return $columns;
}

add_filter( 'manage_posts_columns', 'coldemo_post_columns' );


/**
 * Insert data to wordcount
 */
function coldemo_post_column_data( $column, $post_id ) {
	if ( 'wordcount' == $column ) {
		// $_post = get_post($post_id);
		// $content = $_post->post_content;
		// $wordn = str_word_count(strip_tags($content));
		$wordn = get_post_meta( $post_id, 'wordn', true );
		echo $wordn;
	}
}
add_action( 'manage_posts_custom_column', 'coldemo_post_column_data', 10, 2 );


/**
 * Now let's create a sortable column based on wordcount
 */
function coldemo_sortable_column( $columns ) {
	$columns['wordcount'] = 'wordn'; // we can write anything ( by replace wordn )
	return $columns;
}

add_filter( 'manage_edit-post_sortable_columns', 'coldemo_sortable_column' );


/**
 * Now let's save our meta value ( for wordcount ). we need do this for first time only.
 */

// function coldemo_set_word_count() {
// 	$_posts = get_posts( array(
// 		'posts_per_page' => - 1,
// 		'post_type'      => 'post',
// 		'post_status'    => 'any'
// 	) );

// 	foreach ( $_posts as $p ) {
// 		$content = $p->post_content;
// 		$wordn   = str_word_count( strip_tags( $content ) );
// 		update_post_meta( $p->ID, 'wordn', $wordn );
// 	}
// }

// add_action( 'init', 'coldemo_set_word_count' );


/**
 * Now we need a perfect sortable function
 */
function coldemo_sort_column_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}

	$orderby = $wpquery->get( 'orderby' );
	if ( 'wordn' == $orderby ) {
		$wpquery->set( 'meta_key', 'wordn' );
		$wpquery->set( 'orderby', 'meta_value_num' );
	}
}

add_action( 'pre_get_posts', 'coldemo_sort_column_data' );


/**
 * Now let's save wordcount
 */
function coldemo_update_wordcount_on_post_save($post_id){
	$p = get_post($post_id);
	$content = $p->post_content;
	$wordn   = str_word_count( strip_tags( $content ) );
	update_post_meta( $p->ID, 'wordn', $wordn );
}
add_action('save_post','coldemo_update_wordcount_on_post_save');





/**
 * Lets create a select form for sort data on post
 */

 function coldemo_filter() {
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'post' ) { //display only on posts page
		return;
	}
	$filter_value = isset( $_GET['DEMOFILTER'] ) ? $_GET['DEMOFILTER'] : '';
	$values       = array(
		'0' => __( 'Select Status', 'column_demo' ),
		'1' => __( 'Some Posts', 'column_demo' ),
		'2' => __( 'Some Posts++', 'column_demo' ),
	);
	?>
    <select name="DEMOFILTER">
		<?php
		foreach ( $values as $key => $value ) {
			printf( "<option value='%s' %s>%s</option>", $key,
				$key == $filter_value ? "selected = 'selected'" : '',
				$value
			);
		}
		?>
    </select>
	<?php
}

add_action( 'restrict_manage_posts', 'coldemo_filter' );



/**
 * Let's create a sortable functionality
 */
function coldemo_filter_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}

	$filter_value = isset( $_GET['DEMOFILTER'] ) ? $_GET['DEMOFILTER'] : '';
	if ( '1' == $filter_value ) {
		$wpquery->set( 'post__in', array( 163, 150, 51 ) );
	} else if ( '2' == $filter_value ) {
		$wpquery->set( 'post__in', array( 1788, 1784, 1787 ) );
	}


}

add_action( 'pre_get_posts', 'coldemo_filter_data' );




/**
 * Let's create another filter for thumbnail ( has thumbnail, no thumbnail)
 */

 function coldemo_thumbnail_filter() {
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'post' ) { //display only on posts page
		return;
	}
	$filter_value = isset( $_GET['THFILTER'] ) ? $_GET['THFILTER'] : '';
	$values       = array(
		'0' => __( 'Thumbnail Status', 'column_demo' ),
		'1' => __( 'Has Thumbnail', 'column_demo' ),
		'2' => __( 'No Thumbnail', 'column_demo' ),
	);
	?>
    <select name="THFILTER">
		<?php
		foreach ( $values as $key => $value ) {
			printf( "<option value='%s' %s>%s</option>", $key,
				$key == $filter_value ? "selected = 'selected'" : '',
				$value
			);
		}
		?>
    </select>
	<?php
}

add_action( 'restrict_manage_posts', 'coldemo_thumbnail_filter' );


/**
 * Now let's create thumbnail functionality 
 */
function coldemo_thumbnail_filter_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}

	$filter_value = isset( $_GET['THFILTER'] ) ? $_GET['THFILTER'] : '';
	if ( '1' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS'
			)
		) );
	} else if ( '2' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'NOT EXISTS'
			)
		) );
	}


}

add_action( 'pre_get_posts', 'coldemo_thumbnail_filter_data' );
