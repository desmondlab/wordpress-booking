<?php
/**
 * About This Version administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */
session_start();
/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

wp_enqueue_style( 'wp-mediaelement' );
wp_enqueue_script( 'wp-mediaelement' );
wp_localize_script( 'mediaelement', '_wpmejsSettings', array(
	'pluginPath' => includes_url( 'js/mediaelement/', 'relative' ),
	'pauseOtherPlayers' => ''
) );

$title = __( 'Brands & Models' );

list( $display_version ) = explode( '-', $wp_version );

include( ABSPATH . 'wp-admin/admin-header.php' );
include( ABSPATH . 'wp-admin/booking-script.php' );

$l = ICL_LANGUAGE_CODE;

global $wpdb;
?>

<link rel="stylesheet" href='<?php echo get_template_directory_uri()?>/css/fonts/font-awesome/font-awesome.min.css'>
<link rel="stylesheet" href='<?php echo get_template_directory_uri()?>/plugins/datetimepicker/jquery.datetimepicker.css'>
<!--[if lt IE 9]><script>document.createElement('audio');document.createElement('video');</script><![endif]-->
<div class="wrap ">

    <h1><?php echo __( 'Booking' ) ?></h1>

    <?php if($_SESSION['msg']): ?>
        <div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?php if($_SESSION['msg']=='success'): ?>
                <?php echo __( 'The Class Was Created Successfully' ) ?>
            <?php endif ?>
            
            <?php if($_SESSION['msg']=='del'): ?>
                <?php echo __( 'Deleted Successfully!' ) ?>
            <?php endif ?>
        </div>
    
        <?php if($_SESSION['msg']=='class_overlapped'): ?>
            <div class="alert alert-danger">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo __( 'Requested class overlapped with one or more existing classes.' ) ?><br />
                <?php echo __( 'Please double check the class time very carefully.' ) ?>
            </div>
        <?php endif ?>

        <?php if($_SESSION['msg']=='event_add_failed'): ?>
            <div class="alert alert-danger">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo __( 'Failed to add the class!' ) ?>
            </div>
        <?php endif ?>
    
        <?php if($_SESSION['msg']=='delete_from_manual_enrollment_success'): ?>
            <div class="alert alert-success">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo __( 'Removed the user from class successfully.' ) ?>
            </div>
        <?php endif ?>
    
        <?php if($_SESSION['msg']=='delete_from_manual_enrollment_failed'): ?>
            <div class="alert alert-danger">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo __( 'Failed to remove the user from class!' ) ?>
            </div>
        <?php endif ?>
    
        <?php unset($_SESSION['msg']) ?>
    
    <?php endif ?>

    <h2 class="nav-tab-wrapper">
        <a href="booking.php" class="nav-tab"><?php echo __( 'Booking List' ) ?></a>
        <a href="brands.php" class="nav-tab"><?php echo __( 'Brands & Models' ) ?></a>
        <a href="calendar.php" class="nav-tab nav-tab-active"><?php echo __( 'Calendar' ) ?></a>
    </h2>

    <!-- calendar & class booking form starts here -->
    <div class="col-md-4" style="margin-top: 20px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?php echo __( 'Create Calendar & Classes' ) ?></h4>
            </div>
            <div class="panel-body">
                <?php $category_field = ( $l == 'en' ) ? 'title_en' : 'title' ?>
                <?php $calendar_categories = $wpdb->get_results( "SELECT id, " . $category_field . " FROM wp_booking_cal_category" ) or die(mysql_error()) ?>

                <form id="cooking-class-form" method="POST" action="<?php echo 'booking/calendar_add.php' ?>">

                    <div class="row">
                        <div class="input-group" id="existing_categories">
                            <div class="input-group-addon"><span class="fa fa-calendar-o"></span></div>
                            <select class="col-sm-6" name="cid" id="cid">
                                <?php foreach( $calendar_categories as $category): ?>
                                    <option value="<?php echo $category -> id ?>"><?php echo stripcslashes( $category -> $category_field ) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="input-group" id="new_category" style="display:none;">
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-clipboard"></span></div>
                                <input type="text" class="col-sm-12" name="new_category_name" id="new_cid" placeholder="<?php echo __( 'New Category Name' ) ?>">
                            </div>
                        </div>

                        <div class="checkbox">
                            <label>
                              <input type="checkbox" id="toggle_category_type" value="new_category">
                              <?php echo __( 'Add New Category' ) ?>
                            </label>
                        </div><br />

                        <div class="input-group">
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-file"></span></div>
                                <input type="text" class="col-sm-6" name="title_en" placeholder="<?php echo __( 'Class Name (en)' ) ?>" required>
                                <input type="text" class="col-sm-6" name="title" placeholder="<?php echo __( 'Class Name (?)' ) ?>" required>
                            </div>
                        </div><br />

                        <div class="input-group">
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-file-text"></span></div>
                                <input type="text" class="col-sm-12" name="desc_en" placeholder="<?php echo __( 'Description (en)' ) ?>" required>
                                <input type="text" class="col-sm-12" name="desc" placeholder="<?php echo __( 'Description (?)' ) ?>" required>
                            </div>
                        </div><br />

                        <div class="input-group">
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-calendar"></span></div>
                                <input type="text" class="col-sm-4" name="start_date" id="start_date" placeholder="<?php echo __( 'Start Date' ) ?>" required>
                                <input type="text" class="col-sm-3" name="start" id="start_time" placeholder="<?php echo __( 'Start Time' ) ?>" required>
                                <input type="text" class="col-sm-3" name="end" id="end_time" placeholder="<?php echo __( 'End Time' ) ?>" required>
                            </div>
                        </div>

                    </div>                            

                    <div>
                        <div class="center-block">
                            <input type="hidden" name="status" value="1">
                            <input type="hidden" name="lang" value="<?php echo $l ?>">
                            <button class="btn btn-primary btn-lg btn-block" type="submit"><span class="fa fa-save"></span> <?php echo __( 'Submit' ) ?></button>
                            <button class="btn btn-default btn-lg btn-block" type="reset"><span class="fa fa-chain-broken"></span> <?php echo __( 'Reset' ) ?></button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- calendar & class booking form ends here -->

    <!-- list of calendars & their classes starts here -->
    <div class="col-md-8" style="margin-top: 20px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?php echo __( 'List of Calendars & Classes' ) ?></h4>
            </div>
            <div class="panel-body"><?php echo __( 'List Table' ) ?></div>
            <div class="feature-section">
                <table class="datatable">
                    <thead>
                        <tr>
                            <th><?php echo __( 'Calendar Name' ) ?></th>
                            <th><?php echo __( 'Class Title' ) ?></th>
                            <th><?php echo __( 'Class Date & Time' ) ?></th>
                            <th><?php echo __( 'Enrolled People' ) ?></th>
                            <th><?php echo __( 'Manually Added Users' ) ?></th>
                            <th><?php echo __( 'Actions' ) ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><?php echo __( 'Calendar Name' ) ?></th>
                            <th><?php echo __( 'Class Title' ) ?></th>
                            <th><?php echo __( 'Class Date & Time' ) ?></th>
                            <th><?php echo __( 'Enrolled People' ) ?></th>
                            <th><?php echo __( 'Manually Added Users' ) ?></th>
                            <th><?php echo __( 'Actions' ) ?></th>
                        </tr>
                    </tfoot>
                    
                    <?php $sql = "SELECT wp_booking_cal_category." . $category_field . " AS category,
                                         wp_booking_cal_event." . $category_field . " AS event,
                                         wp_booking_cal_event.id,
                                         wp_booking_cal_event.cid,
                                         wp_booking_cal_event.start_date, 
                                         wp_booking_cal_event.start, 
                                         wp_booking_cal_event.end
                                  FROM wp_booking_cal_event
                                  INNER JOIN wp_booking_cal_category 
                                  ON wp_booking_cal_event.cid = wp_booking_cal_category.id 
                                  ORDER BY wp_booking_cal_event.start_date DESC" ?>
                    
                    <?php $classes = $wpdb -> get_results( $sql ) or die(mysql_error()) ?>
                    <?php foreach( $classes as $class ): ?>
                    
                        <?php $count_enrollment = $wpdb -> get_var ( "SELECT COUNT(*) FROM wp_booking_join WHERE cid = " . $class -> cid . " AND eid = " . $class -> id . " AND status = 1" ) ?>
                        <?php $count_manual_enrollment = $wpdb -> get_var ( "SELECT COUNT(*) FROM wp_booking_join WHERE cid = " . $class -> cid . " AND eid = " . $class -> id . " AND status = 2" ) ?>
                        <?php $total_enrollment = $count_enrollment + $count_manual_enrollment ?>
                    
                        <tr>
                            <td><?php echo stripcslashes( $class -> category ) ?></td>
                            <td><?php echo stripcslashes( $class -> event ) ?></td>
                            <td>
                                <?php $start_date = date( 'd F, Y', strtotime( $class -> start_date ) ) ?>
                                <?php $start_time = date( 'h:i A', strtotime( $class -> start_date . ' ' . $class -> start ) ) ?>
                                <?php $end_time = date( 'h:i A', strtotime( $class -> start_date . ' ' . $class -> end ) ) ?>
                                <?php echo $start_date . '&nbsp;&nbsp;' . $start_time . '&nbsp;-&nbsp;' . $end_time ?>
                            </td>
                            <td>
                                <?php if ( $total_enrollment == 0 ): ?>
                                    <?php echo 'N/A' ?>
                                <?php else: ?>
                                    <span class="badge"><?php echo $total_enrollment ?></span>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if ( $count_manual_enrollment == 0 ): ?>
                                    <?php echo 'N/A' ?>
                                <?php else: ?>
                                    <span class="badge"><?php echo $count_manual_enrollment ?></span>&nbsp;
                                    <a href="manually_enrolled_users.php?calendar_id=<?php echo $class -> cid ?>&event_id=<?php echo $class -> id ?>" class="btn-warning btn btn-xs">
                                        <span class="fa fa-eye"></span> <?php echo __( 'Show Users' ) ?>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn-danger btn btn-xs del-class" alt="<?php echo $class -> cid ?>|<?php echo $class -> id ?>">
                                    <span class="fa fa-trash-o"></span> <?php echo __( 'Delete Class' ) ?>
                                </a>&nbsp;
                                <?php if( $total_enrollment > 0 ): ?>
                                    <a href="booking.php?calendar_id=<?php echo $class -> cid ?>&event_id=<?php echo $class -> id ?>&num_of_enrollments=<?php echo $count_enrollment ?>" class="btn-success btn btn-xs">
                                        <span class="fa fa-users"></span> <?php echo __( 'Enrolled Users' ) ?>
                                    </a>
                                <?php endif ?>
                                <a href="booking_manually.php?calendar_id=<?php echo $class -> cid ?>&event_id=<?php echo $class -> id ?>" class="btn-primary btn btn-xs">
                                    <span class="fa fa-user"></span> <?php echo __( 'Add Enrolled User' ) ?>
                                </a>
                            </td>
                        </tr>
                        
                    <?php endforeach ?>
                    
                </table>
            </div>
        </div>
    </div>
    <!-- list of calendars & their classes ends here -->

</div>

<script src="<?php echo get_template_directory_uri()?>/plugins/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        $("#toggle_category_type").on("click", function () {
            
            $('#new_cid').val('');
            
            if ($(this).prop('checked') == true) {
                $('#new_category').show();
                $('#new_cid').attr('required', 'required');
                
                $('#existing_categories').hide();
                $('#cid').removeAttr('required');
            } else {
                $('#new_category').hide();
                $('#new_cid').removeAttr('required');
                
                $('#existing_categories').show();
                $('#cid').attr('required', 'required');
            }
        });
        
        jQuery('#start_date').datetimepicker({
            timepicker:false,
            format:'Y-m-d'
        });
        
        jQuery('#start_time').datetimepicker({
            datepicker:false,
            format:'H:i'
        });
        
        jQuery('#end_time').datetimepicker({
            datepicker:false,
            format:'H:i'
        });
        
        jQuery('.del-class').on('click', function(e){
            
            if ( confirm( "<?php echo __( 'Are you sure you want to delete this?' ) ?>" )) {
                
                var btn_alt = jQuery(this).attr('alt');
                var btn_alt_array = btn_alt.split( '|' );
                var calendar_id = btn_alt_array[ 0 ];
                var event_id = btn_alt_array[ 1 ];
                
                document.location = "booking/delete_class.php?calendar_id=" + calendar_id + "&event_id=" + event_id;
            }
        });

    });
</script>