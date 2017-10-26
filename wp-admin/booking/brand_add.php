<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );

global $wpdb;

$brand_data[ 'title_en' ] = trim( $_POST[ 'title_en' ] );
$brand_data[ 'title' ] = trim( $_POST[ 'title' ] );
$brand_data[ 'status' ] = $_POST[ 'status' ];

//$wpdb->insert( 'wp_booking_cal_category', $_POST );
$wpdb->insert( 'wp_booking_brand', $brand_data );
$new_brand_id = $wpdb -> insert_id;

$_SESSION['msg'] = 'success';

header('Location:../brands.php?new_brand_id=' . $new_brand_id);

ob_end_flush();

?>