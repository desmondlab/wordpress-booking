<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );

global $wpdb;

$join_data = array( 'create_time' => Date('Y-m-d H:i:s'), 
                    'uid' => $_POST[ 'id' ], 
                    'cid' => $_POST[ 'cid' ], 
                    'eid' => $_POST[ 'chosen-class' ], 
                    'lang' => $_POST[ 'lang' ], 
                    'status' => 1
                  );

$save_data = $wpdb -> insert( 'wp_booking_join', $join_data );
        
        
if ( $save_data ) {

    $wpdb -> update( 'wp_booking_user', array( 'cid' => 0, 
                                               'status' => 2, 
                                               'join' => 1 ), 
                                        array( 'id' => $_POST[ 'id' ] ) );

    //send the user a confirmation email
    $email = $_POST[ 'email' ];

    $class_info = $wpdb -> get_row( "SELECT * FROM wp_booking_cal_event WHERE cid = " . $_POST[ 'cid' ] . " AND id = " . $_POST[ 'chosen-class' ] );

    $class_name = ( $l == 'en' ) ? $class_info -> title_en : $class_info -> title;
    $class_desc = ( $l == 'en' ) ? $class_info -> desc_en : $class_info -> desc;

    $class_starts_at = $class_info -> start_date . ' ' . $class_info -> start;
    $class_ends_at = $class_info -> start_date . ' ' . $class_info -> end;

    $class_start_date = date( 'd-m-Y', strtotime( $class_starts_at ) );
    $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
    $class_end_time = date( 'h:i a', strtotime( $class_ends_at ) );

    $email_message = '<p>Dear valued customer,<br><br>';
    $email_message .= 'Thanks for your class booking, detail listed as below.<br>';
    $email_message .= 'We will send a confirmation email prior to 7 days before the class date.<br><br>';
    $email_message .= '<br>';
    $email_message .= 'Class: <strong>' . stripcslashes( $class_name ) . '</strong><br>';
    $email_message .= 'Date: <strong>' . $class_start_date . '</strong><br>';
    $email_message .= 'Time: <strong>' . $class_start_time . '</strong><br>';
    $email_message .= 'Venue: <strong>2/F, Fortress, Shop A, Pun Tak Building, 478-484 Lockhart Road, Causeway Bay, Hong Kong</strong><br><br>';

    $email_message .= __( 'Best Regards,' ) . '<br>';
    $email_message .= __( 'Janet Cheung' ) . '<br>';
    $email_message .= '<img src="http://delonghiacademy.com.hk/wp-content/themes/lotus/images/email_signature.png"/><br>';
    $email_message .= "De' Longhi Kenwood A.P.A. Ltd.<br>";
    $email_message .= __( '16/F., Tins Enterprises Centre, 777 Lai Chi Kok Road, Cheung Sha Wan, Kowloon, Hong Kong' ) . '<br>';
    $email_message .= __( 'Janet.cheung@delonghigroup.com' );
    $email_message .= '</p>';

    $headers = array('Content-Type: text/html; charset=UTF-8', 'Delonghi HK <info.hk@delonghigroup.com>');
    $subject = 'Delonghi HK - Cooking Class Booking Confirmation';

    add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
    wp_mail( $email, $subject, $email_message, $headers );
    
    $_SESSION['msg'] = 'booking_success';

} else {

    $_SESSION['msg'] = 'booking_failed';
}

header('Location:../booking.php');

ob_end_flush();
?>