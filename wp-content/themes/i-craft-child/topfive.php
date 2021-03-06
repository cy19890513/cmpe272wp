<?php
/*
Template Name: TopFive
*/
/**
 * Created by PhpStorm.
 * User: yzhao
 * Date: 11/29/18
 * Time: 12:24 AM
 */
//include_once('../wp-includes/class-wp-query.php');
function reorder_by_viewCount($a, $b) {
    $a_viewCount = $a->viewCount;
    $b_viewCount = $b->viewCount;
    return $a_viewCount < $b_viewCount ? 1 : -1;
}


$query_args = array(
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'post_type'      => 'product',
);
$r = new WP_Query($query_args);
$objs = array() ;
while ($r->have_posts()) {
    $r->the_post();
    global $product;
    $myobj->name = $product->get_name();
    $myobj->price = $product->get_price();
    $myobj->productCode = $product->get_id();
    $myobj->averageRating = $product->get_average_rating();
    $myobj->viewCount= get_post_meta($product->get_id(), '_product_view_count', true );
    $myobj->thumbnail=get_the_post_thumbnail_url($product->get_id());
    $myobj->clickTo=$product->get_permalink();
    $myobj->description=$product->get_description();
    $objs[] = clone $myobj;
}
usort($objs,'reorder_by_viewCount');
$new_objs = array_slice($objs,0,5,true);
echo json_encode($new_objs);

?>