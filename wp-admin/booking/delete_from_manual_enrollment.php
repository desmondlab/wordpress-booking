<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
//include_once( $_SERVER['DOCUMENT_ROOT'] . '/projects/class_cooking/wp-config.php' );

global $wpdb;

$calendar_id = $_GET['calendar_id'];
$event_id = $_GET['event_id'];
$join_id = $_GET['join_id'];

$update = $wpdb -> update( 'wp_booking_join', array( 'status' => -1 ), 
                                              array( 'id' => $join_id ) );

if ( $update !== FALSE )
    $_SESSION['msg'] = 'delete_from_manual_enrollment_success';
else
    $_SESSION['msg'] = 'delete_from_manual_enrollment_failed';

header('Location:../calendar.php');

ob_end_flush();
?>