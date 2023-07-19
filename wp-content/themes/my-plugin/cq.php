<?php
/**
 * Template Name: Custom query template
 */
?>
<?php get_header(); ?>
<?php
$posts_per_page = 2;
$_p = new WP_Query(
    array(
    'posts_per_page' => $posts_per_page,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'featured',
            'value' => '1',
            'compare' => '='
        ),
        array(
            'key' => 'homepage',
            'value' => '1',
            'compare' => '='
        )
    )
));

while($_p->have_posts()) {
    $_p->the_post(); ?>
    <h1 class="title"><?php the_title(); ?></h1>
<?php } wp_reset_query();;
?>
<?php get_footer(); ?>