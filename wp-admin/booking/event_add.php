<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
global $wpdb;
$wpdb->insert( 'wp_booking_cal_event', $_POST );

$_SESSION['msg'] = 'success';

header('Location:../calendar.php');

?>