<?php

/**
 * WordPress Cron Implementation for hosts, which do not offer CRON or for which
 * the user has not set up a CRON job pointing to this file.
 *
 * The HTTP request to this file will not slow down the visitor who happens to
 * visit when the cron job is needed to run.
 *
 * @package WordPress
 */
ignore_user_abort ( true );

if ( ! empty ( $_POST ) || defined ( 'DOING_AJAX' ) || defined ( 'DOING_CRON' ) )
    die ();

/**
 * Tell WordPress we are doing the CRON task.
 *
 * @var bool
 */
define ( 'DOING_CRON', true );

if ( ! defined ( 'ABSPATH' ) ) {
    /** Set up WordPress environment */
    require_once( dirname ( __FILE__ ) . '/wp-load.php' );
}

// Uncached doing_cron transient fetch
function _get_cron_lock ()
{
    global $wpdb;

    $value = 0;
    if ( wp_using_ext_object_cache () ) {
        /*
         * Skip local cache and force re-fetch of doing_cron transient
         * in case another process updated the cache.
         */
        $value = wp_cache_get ( 'doing_cron', 'transient', true );
    } else {
        $row = $wpdb -> get_row ( $wpdb -> prepare ( "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", '_transient_doing_cron' ) );
        if ( is_object ( $row ) )
            $value = $row -> option_value;
    }

    return $value;
}

if ( false === $crons = _get_cron_array () )
    die ();

$keys = array_keys ( $crons );
$gmt_time = microtime ( true );

if ( isset ( $keys[ 0 ] ) && $keys[ 0 ] > $gmt_time )
    die ();

$doing_cron_transient = get_transient ( 'doing_cron' );

// Use global $doing_wp_cron lock otherwise use the GET lock. If no lock, trying grabbing a new lock.
if ( empty ( $doing_wp_cron ) ) {
    if ( empty ( $_GET[ 'doing_wp_cron' ] ) ) {
        // Called from external script/job. Try setting a lock.
        if ( $doing_cron_transient && ( $doing_cron_transient + WP_CRON_LOCK_TIMEOUT > $gmt_time ) )
            return;
        $doing_cron_transient = $doing_wp_cron = sprintf ( '%.22F', microtime ( true ) );
        set_transient ( 'doing_cron', $doing_wp_cron );
    } else {
        $doing_wp_cron = $_GET[ 'doing_wp_cron' ];
    }
}

// Check lock
if ( $doing_cron_transient != $doing_wp_cron )
    return;

foreach ( $crons as $timestamp => $cronhooks ) {
    if ( $timestamp > $gmt_time )
        break;

    foreach ( $cronhooks as $hook => $keys ) {

        foreach ( $keys as $k => $v ) {

            $schedule = $v[ 'schedule' ];

            if ( $schedule != false ) {
                $new_args = array ( $timestamp, $schedule, $hook, $v[ 'args' ] );
                call_user_func_array ( 'wp_reschedule_event', $new_args );
            }

            wp_unschedule_event ( $timestamp, $hook, $v[ 'args' ] );

            /**
             * Fires scheduled events.
             *
             * @internal
             * @since 2.1.0
             *
             * @param string $hook Name of the hook that was scheduled to be fired.
             * @param array  $args The arguments to be passed to the hook.
             */
            do_action_ref_array ( $hook, $v[ 'args' ] );

            // If the hook ran too long and another cron process stole the lock, quit.
            if ( _get_cron_lock () != $doing_wp_cron )
                return;
        }
    }
}

if ( _get_cron_lock () == $doing_wp_cron )
    delete_transient ( 'doing_cron' );


add_action ( 'cooking_class_hook', 'cooking_class' );

function activate ()
{
    wp_schedule_event ( time (), 'hourly', 'cooking_class_hook' );
}// end activate

function deactivate ()
{
    wp_clear_scheduled_hook ( 'cooking_class_hook' );
}// end activate

function cooking_class ()
{
    global $wpdb;

    //check if any user didn't book any class within the deadline
    $check_booking_delays = $wpdb -> get_results ( 'SELECT * FROM wp_booking_user WHERE status = 1 AND deadline < NOW()' );
    if ( count ( $check_booking_delays ) > 0 ) { //delayed users found; inactive them
        foreach ( $check_booking_delays as $delayed ){
            $user_id = $delayed -> id;
            $wpdb -> update ( 'wp_booking_user', array ( 'status' => 3 ), array ( 'id' => $user_id ) );
        }
    }

    //check if the enrolment of the class < 3 prior 7 days of the class date; also check if the enrolment of the class >= 3 prior 7 days of the class date
    $check_less_than_min_attendances = $wpdb -> get_results ( 'SELECT * FROM wp_booking_cal_event WHERE status = 1 AND confirmed_by_mail = 0 AND DATE_SUB(start_date,INTERVAL 7 DAY) = CURDATE()' );
    if ( count ( $check_less_than_min_attendances ) > 0 ) {

        foreach ( $check_less_than_min_attendances as $attendance ) {

            $calendar_id = $attendance -> cid;
            $event_id = $attendance -> id;
            $class_name = $attendance -> title_en;

            $class_starts_at = $attendance -> start_date . ' ' . $attendance -> start;
            $class_start_date = date ( 'd-m-Y', strtotime ( $class_starts_at ) );
            $class_start_time = date ( 'H:i', strtotime ( $class_starts_at ) );

            $attendance_count = $wpdb -> get_var ( "SELECT COUNT(*) FROM wp_booking_join WHERE cid = " . $calendar_id . " AND eid = " . $event_id . " AND status = 1" );

            if ( ( $attendance_count < 3 ) || ( $attendance_count >= 3 ) ) {

                $sql = "SELECT wp_booking_user.email, wp_booking_user.serial, wp_booking_user.deadline "
                        . "FROM wp_booking_join "
                        . "INNER JOIN wp_booking_user ON wp_booking_join.uid = wp_booking_user.id "
                        . "WHERE wp_booking_join.cid = " . $calendar_id . " "
                        . "AND wp_booking_join.eid = " . $event_id . " "
                        . "AND wp_booking_join.status = 1";

                $query_user = $wpdb -> get_results ( $sql );

                if ( count ( $query_user ) > 0 ) { // sent notifications to the users
                    if ( $attendance_count < 3 ) {
                        $wpdb -> update ( 'wp_booking_join', array ( 'status' => 0 ), array ( 'cid' => $calendar_id,
                            'eid' => $event_id ) );
                    }

                    foreach ( $query_user as $user_data ) {

                        $user_id = $user_data -> id;
                        $email = $user_data -> email;
                        $deadline = date ( 'd-m-Y', strtotime ( $user_data -> deadline ) );

                        $serial = $user_data -> serial;
                        $user_pass = substr ( $serial, 0, 4 );

                        $email_message = '<p>' . __ ( 'Dear valued customer,' ) . '<br><br>';

                        if ( $attendance_count < 3 ) {
                            $wpdb -> update ( 'wp_booking_user', array ( 'status' => 1 ), array ( 'id' => $user_id ) );
                            $email_message .= __ ( 'I am sorry to notify you that the class you registered earlier is cancelled, due to low enrollment. You may use the below ID and password to book another class.' ) . '<br><br>';
                        }

                        if ( $attendance_count >= 3 ) {
                            $email_message .= __ ( 'A friendly reminder that your class enrolled will happen soon. Please bring along the receipt and class invitation letter for validation.' ) . '<br><br>';
                        }

                        $email_message .= __ ( 'Class' ) . ': <strong>' . $class_name . '</strong><br>';
                        $email_message .= __ ( 'Date' ) . ': <strong>' . $class_start_date . '</strong><br>';
                        $email_message .= __ ( 'Time' ) . ': <strong>' . $class_start_time . '</strong><br>';
                        $email_message .= __ ( 'Venue' ) . ': <strong>2/F, Fortress, Shop A, Pun Tak Building, 478-484 Lockhart Road, Causeway Bay, Hong Kong</strong><br><br>';

                        $email_message .= __ ( 'Login ID' ) . ': <strong>' . $login_id . '</strong><br>';
                        $email_message .= __ ( 'Password' ) . ': <strong>' . $login_pass . '</strong><br>';
                        $email_message .= __ ( 'Valid Date' ) . ': ' . __ ( 'Until' ) . ' <strong>' . date ( 'd-m-Y', strtotime ( $deadline ) ) . '</strong><br>';
                        $email_message .= __ ( 'Booking Link : http://delonghiacademy.com.hk/cooking-class/' ) . '<br><br>';

                        $email_message .= __( 'Best Regards,' ) . '<br>';
                        $email_message .= __( 'Janet Cheung' ) . '<br>';
                        $email_message .= '<img src="http://delonghiacademy.com.hk/wp-content/themes/lotus/images/email_signature.png"/><br>';
                        $email_message .= "De' Longhi Kenwood A.P.A. Ltd.<br>";
                        $email_message .= __( '16/F., Tins Enterprises Centre, 777 Lai Chi Kok Road, Cheung Sha Wan, Kowloon, Hong Kong' ) . '<br>';
                        $email_message .= __( 'Janet.cheung@delonghigroup.com' );
                        $email_message .= '</p>';

                        $headers = array ( 'Content-Type: text/html; charset=UTF-8', 'Delonghi HK <info.hk@delonghigroup.com>' );
                        $subject = 'Delonghi HK - Cooking Class Registration';

                        wp_mail ( $email, $subject, $email_message, $headers );

                        $wpdb -> update ( 'wp_booking_cal_event', array ( 'confirmed_by_mail' => 1 ), array ( 'id' => $event_id, 'cid' => $calendar_id ) );
                    }// end sub foreach
                }//end sub-if
            }//end sub-if
        }//end foreach
    }//end if
}// end cooking_clas

die ();