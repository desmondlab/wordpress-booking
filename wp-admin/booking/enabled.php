<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );

global $wpdb;

$today = Date( 'Y-m-d' );
$deadline = date( 'Y-m-d', strtotime( "+3 months", strtotime( $today ) ) );

$update = $wpdb -> update( 'wp_booking_user', array( 'status' => 1, 
                                                     'deadline' => $deadline,
                                                     'cid' => $_POST[ 'cid' ],
                                                     'join' => 0 ), 
                                              array( 'id' => $_POST[ 'id' ] ) );

if ( $update !== FALSE ) {
   
    $_SESSION['msg'] = 'enabled';
    
    //testing for generating calendar link starts here
    $cid = $_POST['cid'];
    $uid = $_POST['id'];
    $c = md5('cat');
    $i = md5('uid');
    
    $_SESSION[ 'calendar_link' ] = $calendar_link;
    
    $login_id = $_POST[ 'email' ];
    $login_pass = substr( $_POST[ 'serial' ], 0, 4 );
    
    $email_message = '<p>' . __( 'Dear valued customer,' ) . '<br><br>';
    
    if ( $_POST[ 'status' ] == 0 ) {
        $email_message .= __( 'Your information is verified, please kindly find your login ID and password for online class booking below.' ) . '<br>';
    } elseif ( $_POST[ 'status' ] > 1 ) {
        $email_message .= __( 'Your account was reactivated, please kindly find your login ID and password for online class booking below.' ) . '<br>';
    }
    
    
    $email_message .= __( 'Login ID' ) . ': <strong>' . $login_id . '</strong><br>';
    $email_message .= __( 'Password' ) . ': <strong>' . $login_pass . '</strong><br>';
    $email_message .= __( 'Valid Date' ) . ': ' . __( 'Until' ) . ' <strong>' . date( 'd-m-Y', strtotime( $deadline ) ) . '</strong><br>';
    $email_message .= __( 'Booking Link : http://delonghiacademy.com.hk/cooking-class/' ) . '<br><br>';
    
    $email_message .= __( 'Best Regards,' ) . '<br>';
    $email_message .= __( 'Janet Cheung' ) . '<br>';
    $email_message .= '<img src="http://delonghiacademy.com.hk/wp-content/themes/lotus/images/email_signature.png"/><br>';
    $email_message .= "De' Longhi Kenwood A.P.A. Ltd.<br>";
    $email_message .= __( '16/F., Tins Enterprises Centre, 777 Lai Chi Kok Road, Cheung Sha Wan, Kowloon, Hong Kong' ) . '<br>';
    $email_message .= __( 'Janet.cheung@delonghigroup.com' ) . '<br>';
    $email_message .= __( 'www.delonghigroup.com' );
    $email_message .= '</p>';
    
    $headers = array('Content-Type: text/html; charset=UTF-8', 'Delonghi HK <info.hk@delonghigroup.com>');
    $subject = "De'Longhi Group Academy - Class Registration";

    wp_mail( $_POST['email'], $subject, $email_message, $headers );  
    
} else {
    $_SESSION['msg'] = 'enabling_failed';
}

header('Location:../booking.php');

ob_end_flush();
?>