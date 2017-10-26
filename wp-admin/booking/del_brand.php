<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
global $wpdb;
$id = $_GET['id'];
$wpdb->delete( 'wp_booking_brand', array( 'id' => $id ) );

echo $id;
$_SESSION['msg'] = 'del';

header('Location:../brands.php');

?>