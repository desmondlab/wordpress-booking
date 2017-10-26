<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
global $wpdb;

$_POST['create_time'] = Date('Y-m-d H:i:s');
$_POST['status'] = 1;


$wpdb->insert( 'wp_booking_join', $_POST );

$wpdb->update('wp_booking_user', array('join'=>'1'), array('id'=>$_POST['uid']));

header('Location:/thanks-for-your-registration/?lang='.$_POST['lang']);
