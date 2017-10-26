<?php
# Load slim WP
define( 'WP_USE_THEMES', false );
require( dirname ( __FILE__ ) . '../../wp-load.php' );

require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';

global $wpdb;

/** eheading **/
$products = $wpdb->get_results( "SELECT * FROM wp_booking_user ORDER BY id DESC", ARRAY_A );

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()
		->setCreator("user")
    		->setLastModifiedBy("user")
		->setTitle("Members")
		->setSubject("Members")
		->setDescription("Exported Members.")
		->setKeywords("office 2003")
		->setCategory("Exported members file");

// Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0);

// Initialise the Excel row number
$rowCount = 0;

// Sheet cells
$cell_definition = array(
	'A' => 'id',
    'B' => 'registered at',
	'C' => 'user name',
	'D' => 'phone',
	'E' => 'email',
    'F' => 'brand',
    'G' => 'model',
	'H' => 'retail outlet',
	'I' => 'delivery date',
    'J' => 'invoice image',
    'K' => 'invitation letter',
	'L' => 'class in english only',
    'M' => 'deadline',
    'N' => 'class enrolled'
);
// Build headers
$cell_headers = array(
	'A' => 'id',
    'B' => 'registered at',
	'C' => 'user name',
	'D' => 'phone',
	'E' => 'email',
    'F' => 'brand',
    'G' => 'model',
	'H' => 'retail outlet',
	'I' => 'delivery date',
    'J' => 'invoice image',
    'K' => 'invitation letter',
	'L' => 'class in english only',
    'M' => 'deadline',
    'N' => 'class enrolled'
);

foreach ($products as $row){
    
    //brand
    $brand = "SELECT * FROM wp_booking_brand WHERE id = " . $row['bid'];
    $brand_result = $wpdb->get_results($brand) or die(mysql_error());
    
    //model
    $model_id = $row['model'];
    if (!empty($model_id) && ( $model_id > 0 ))
        $query_model = "SELECT * FROM wp_booking_model WHERE id = " . $row['model'];
        
    $result_model = $wpdb->get_results($query_model) or die(mysql_error());
    
    //class enrolled
    $booking_sql = "SELECT wp_booking_cal_event.title_en, wp_booking_cal_event.start_date, wp_booking_cal_event.start, wp_booking_cal_event.end "
                                                    . "FROM wp_booking_cal_event "
                                                    . "RIGHT JOIN wp_booking_join "
                                                    . "ON wp_booking_join.eid = wp_booking_cal_event.id "
                                                    . "WHERE wp_booking_join.uid = " . $row['id']  . " ";
                                            
    $user_info = $wpdb -> get_row( $booking_sql );
                                            
                                            if (count( $user_info ) > 0 ) {
                                                
                                                $class_title = stripcslashes( $user_info -> title_en );
                                                
                                                $class_starts_at = $user_info -> start_date . ' ' . $user_info -> start;
                                                $class_ends_at = $user_info -> start_date . ' ' . $user_info -> end;
                                                $class_start_date = date( 'F d, Y', strtotime( $class_starts_at ) );
                                                $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
                                                $class_end_time = date( 'h:i a', strtotime( $class_ends_at ) );
                                                
                                                $enrolled_class = $class_title . ' ( ' . $class_start_date . ': from ' . $class_start_time . ' to ' . $class_end_time . ' )';
                                            } else {
                                                $enrolled_class = '';
                                            }
    
    $export_members[] = array(
        "id" => $row['id'],
        "registered at" => $row['create_time'],
        "user name" => $row['fname'] . " " . $row['lname'],
        "phone" => $row['phone'],
        "email" => $row['email'],
        "brand" => $brand_result[0]->title_en,
        "model" => $result_model[0]->title,
        "retail outlet" => $row['retail_outlet'],
        "delivery date" => $row['delivery_date'],
        "invoice image" => $row['invoice_img'],
        "invitation letter" => $row['invitation_letter_img'],
        "class in english only" => ($row['cooking_class_in_english'] == 1) ? "Yes" : "No" ,
        "deadline" => $row['deadline'],
        "class enrolled" => $enrolled_class
    );
}


foreach( $cell_headers as $column => $value )
	$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value );

// Build cells
while( $rowCount < count($export_members) ){
	$cell = $rowCount + 2;
	foreach( $cell_definition as $column => $value )
		$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $export_members[$rowCount][$value]);

    $rowCount++;
}
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Members.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;