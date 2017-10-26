<?php
session_start();

ob_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
//include_once( $_SERVER['DOCUMENT_ROOT'] . '/projects/class_cooking/wp-config.php' );

global $wpdb;


if ( !empty( $_POST[ 'name' ] ) && !empty( $_POST[ 'phone' ] ) ) { 
    
    $data  = array( 'name' => $_POST[ 'name' ],
                    'phone' => $_POST[ 'phone' ],
                    'created_at' => date( 'Y-m-d H:i:s' ) );
    
    if ( $wpdb->insert( 'wp_booking_user_manually', $data ) ) {
        
        $user_id = $wpdb -> insert_id;
        
        $join_data = array( 'create_time' => date('Y-m-d H:i:s'), 
                            'uid' => $user_id, 
                            'cid' => $_POST[ 'calendar_id' ], 
                            'eid' => $_POST[ 'event_id' ], 
                            'lang' => $_POST[ 'lang' ], 
                            'status' => 2
                          );

        if ( $wpdb -> insert( 'wp_booking_join', $join_data ) )
            $_SESSION[ 'msg' ] = 'success';
        else
            $_SESSION[ 'msg' ] = 'failure';
        
    } else {
        $_SESSION[ 'msg' ] = 'failure';
    }
        
    
    
}// end main if

header('Location:../booking_manually.php?calendar_id=' . $_POST[ 'calendar_id' ] . '&event_id=' . $_POST[ 'event_id' ]);

ob_end_flush();

?>