<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );

global $wpdb;

$status = $_POST[ 'status' ];
$lang = $_POST[ 'lang' ];
$category_field = ( $lang == 'en' ) ? 'title_en' : 'title';

if ( !empty( $_POST[ 'new_category_name' ] ) ) { //requested to create a new calendar category
    
    $new_category_name = $_POST['new_category_name'];
    //check if the category already exists
    $check_category = $wpdb->get_row( "SELECT id FROM $wpdb->wp_booking_cal_category WHERE $category_field = '" . $new_category_name . "'" );
    if ( count ( $check_category ) > 0 ) {

        $category_id = $check_category -> id;

    } else { //insert a new category to get a new id

        $new_category_data  = array( $category_field => $new_category_name,
                                     'status' => $status );
        if ( $wpdb->insert( 'wp_booking_cal_category', $new_category_data ) )
            $category_id = $wpdb -> insert_id;

    }

} else { // selected an existing calendar category
    $category_id = $_POST[ 'cid' ];
}

//now gather data to save calendar event
$event_data = array( 'cid' => $category_id,
                     'title' => $_POST[ 'title' ],
                     'title_en' => $_POST[ 'title_en' ],
                     'desc' => $_POST[ 'desc' ],
                     'desc_en' => $_POST[ 'desc_en' ],
                     'start_date' => $_POST[ 'start_date' ],
                     'start' => $_POST[ 'start' ],
                     'end' => $_POST[ 'end' ],
                     'status' => $_POST[ 'status' ] 
                   );

$startTime = date( 'Y-m-d', strtotime( $event_data[ 'start_date' ] ) ) . ' ' . $event_data[ 'start' ] . ':00';
$endTime = date( 'Y-m-d', strtotime( $event_data[ 'start_date' ] ) ) . ' ' . $event_data[ 'end' ] . ':00';

$check_class_overlapping = $wpdb -> get_results( "SELECT title FROM wp_booking_cal_event WHERE (ADDTIME(start_date, end) > '" . $startTime . "') AND (ADDTIME(start_date, start) < '" . $endTime . "')" );

if (count($check_class_overlapping) > 0 ) {
    
    $_SESSION[ 'msg' ] = 'class_overlapped';
    
} else {
    
    $save_event = $wpdb->insert( 'wp_booking_cal_event', $event_data );

    if ( $save_event )
        $_SESSION[ 'msg' ] = 'success';
    else
        $_SESSION[ 'msg' ] = 'event_add_failed';
    
}

header('Location:../calendar.php');

ob_end_flush();

?>