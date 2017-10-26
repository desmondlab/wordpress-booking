<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );

global $wpdb;

$calendar_id = $_GET['calendar_id'];
$event_id = $_GET['event_id'];

$delete_class = $wpdb->delete( 'wp_booking_cal_event', array( 'id' => $event_id ) );

if ( $delete_class !== false ) {
    
    $_SESSION['msg'] = 'del';
    
    $check_joined_users = $wpdb -> get_results( "SELECT uid FROM wp_booking_join WHERE cid=" . $calendar_id . " AND eid=" . $event_id );
         
    if ( count( $check_joined_users ) > 0 ) {
        
        foreach ($check_joined_users as $user) {
            
            $user_id = $user -> uid;
            $wpdb -> update( 'wp_booking_user', array( 'cid' => 0, 
                                                       'status' => 0, 
                                                       'join' => 0 ), 
                                                       array( 'id' => $user_id ) );
        }
        
        $wpdb->delete( 'wp_booking_join', array( 'cid' => $calendar_id, 
                                                 'eid' => $event_id ) );
    }
}

header('Location:../calendar.php');

ob_end_flush();
?>