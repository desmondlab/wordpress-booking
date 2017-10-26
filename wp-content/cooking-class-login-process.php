<?php
/* Template Name: BOOKING CLASS LOGIN PROCESS */

session_start();

ob_start();

$l = ICL_LANGUAGE_CODE;

if ( $l == 'en' ) {
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-booking-login';
    $calendar_url = 'http://' . $_SERVER['SERVER_NAME'] . '/calendar';
    
} else { 
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-booking-login/?lang=zh-hant';
    $calendar_url = 'http://' . $_SERVER['SERVER_NAME'] . '/calendar/?lang=zh-hant';
}

$class_booked_already = false;
$class_booking_delayed = false;

if ( isset( $_POST[ 'login-id' ] ) ) {
    
    global $wpdb;
    
    $email = $_POST[ 'login-id' ];
    $user_pass = sha1( $_POST[ 'login-pass' ] );
    
    $check_credential = $wpdb->get_row( "SELECT * FROM wp_booking_user WHERE email = '" . $email . "' AND user_pass = '" . $user_pass . "'" );
    
    if ( null !== $check_credential ) { // credential ok
        
        $user_data = $wpdb -> get_row( "SELECT * FROM wp_booking_user WHERE email = '" . $email . "'" );
        $user_status = $user_data -> status;
        
        if ( $user_status == 1 ) { //admin assigned a calendar
            $_SESSION['cat'] = $user_data -> cid;
            $_SESSION['uid'] = $user_data -> id;

            header('Location:' . $calendar_url);
            ob_end_flush();
        }
        
        if ( $user_status == 2 ) {
            $class_booked_already = true;
            $_SESSION[ 'error_message' ] = __( 'Sorry, you have already registered a class with this login ID. Each login ID can only register one class.' );
        }
        
        if ( $user_status == 3 ) {
            $class_booking_delayed = true;
            $_SESSION[ 'error_message' ] = __( 'Sorry, your account was invalid as you have not used it within 3 months.' );
        }
        
        if ( $user_status == 4 ) {
            $_SESSION[ 'error_message' ] = __( 'Sorry, you are not qualified enough to book any classes. Please contact to the admin.' );
        }
        
        if ( ( $class_booked_already !== false ) || ( $class_booking_delayed !== false ) ) {
            header('Location:' . $login_url);
            ob_end_flush();
        }
        
        if ( $user_status == 0 ) {
            $_SESSION[ 'error_message' ] = __( 'Sorry, no calendar is assigned to you. Please contact to the admin.' );
        }
    } else {
        $_SESSION[ 'error_message' ] = __( '<strong>ERROR</strong>: Invalid Login ID or incorrect Password.' );

        header('Location:' . $login_url);

        ob_end_flush();
      }
    
    
}// end main if
?>