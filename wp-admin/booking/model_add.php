<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
global $wpdb;

$data = array( 'bid' => $_POST[ 'bid' ],
               'title' => $_POST[ 'title' ],
               'status' => $_POST[ 'status' ] );

$wpdb->insert( 'wp_booking_model', $data );

$_SESSION['msg'] = 'success';

header('Location:../brands.php');

?>