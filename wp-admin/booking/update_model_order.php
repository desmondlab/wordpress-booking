<?php

if ( isset($_REQUEST) ) {
    
    $model_id = $_REQUEST[ 'model_id' ];
    $order_value = $_REQUEST[ 'order_value' ];
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
    
    global $wpdb;    
    
    $response = array();
    
    $update = $wpdb -> update( 'wp_booking_model', array( 'sort_by' => $order_value ), 
                                                   array( 'id' => $model_id ) );
    
    if ( $update !== false ) {

        $response[ 'status' ] = 'success';
        $response[ 'message' ] = __( 'Updated.' );

    } else {

        $response[ 'status' ] = 'failure';
        $response[ 'message' ] = __( 'Save failed.' );
    }
    
    echo json_encode( $response );    
    
}//end main if