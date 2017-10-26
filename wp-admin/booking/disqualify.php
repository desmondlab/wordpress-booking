<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );

global $wpdb;

$user_id = $_GET[ 'user_id' ];

$update = $wpdb -> update( 'wp_booking_user', array( 'status' => 4, 
                                                     'cid' => 0,
                                                     'join' => 0 ), 
                                              array( 'id' => $user_id ) );

if ( $update !== FALSE ) {
   
    $_SESSION['msg'] = 'disqualified';
    
    //send the user a disqualification email
    $user_info = $wpdb -> get_row( "SELECT email FROM wp_booking_user WHERE id = " . $user_id );
    $email = $user_info -> email;
    
    $email_message = '<p>' . __( 'Dear valued customer,' ) . '<br><br>';    
    $email_message .= __( 'Your information submitted was invalid. Please contact us for more information.' ) . '<br>';    
    
    $email_message .= __( 'Best Regards,' ) . '<br>';
    $email_message .= __( 'Janet Cheung' ) . '<br>';
    $email_message .= '<img src="http://delonghiacademy.com.hk/wp-content/themes/lotus/images/email_signature.png"/><br>';
    $email_message .= "De' Longhi Kenwood A.P.A. Ltd.<br>";
    $email_message .= __( '16/F., Tins Enterprises Centre, 777 Lai Chi Kok Road, Cheung Sha Wan, Kowloon, Hong Kong' ) . '<br>';
    $email_message .= __( 'Janet.cheung@delonghigroup.com' );
    $email_message .= '</p>';
    
    $headers = array('Content-Type: text/html; charset=UTF-8', 'Delonghi HK <info.hk@delonghigroup.com>');
    $subject = "De'Longhi Group Academy - Class Registration";

    wp_mail( $email, $subject, $email_message, $headers );  
    
} else {
    $_SESSION['msg'] = 'disqualification_failed';
}

header('Location:../booking.php');

ob_end_flush();
?>