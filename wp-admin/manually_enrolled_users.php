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
<!--[if lt IE 9]><script>document.createElement('audio');document.createElement('video');</script><![endif]-->
<div class="wrap ">

    <?php if($_SESSION['msg']): ?>
        <div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?php if($_SESSION['msg']=='success'): ?>
                <?php echo __( 'Class booked successfully' ) ?>
            <?php endif ?>
            
            <?php if($_SESSION['msg']=='failure'): ?>
                <?php echo __( 'Failed to book the class!' ) ?>
            <?php endif ?>
        </div>
    
        <?php unset($_SESSION['msg']) ?>
    
    <?php endif ?>
    
    <?php $category_field = ( $l == 'en' ) ? 'title_en' : 'title' ?>
    <?php $calendar_categories = $wpdb->get_results( "SELECT id, " . $category_field . " FROM wp_booking_cal_category" ) or die(mysql_error()) ?>

    <h2 class="nav-tab-wrapper">
        <a href="booking.php" class="nav-tab"><?php echo __( 'Booking List' ) ?></a>
        <a href="brands.php" class="nav-tab"><?php echo __( 'Brands & Models' ) ?></a>
        <a href="calendar.php" class="nav-tab"><?php echo __( 'Calendar' ) ?></a>
    </h2>
    
    <?php $sql = "SELECT wp_booking_join.create_time AS join_date, wp_booking_join.cid, wp_booking_join.eid, wp_booking_join.lang, wp_booking_join.id,
                         wp_booking_user_manually.name, wp_booking_user_manually.phone, wp_booking_user_manually.created_at
                  FROM wp_booking_join
                  INNER JOIN wp_booking_user_manually
                          ON wp_booking_join.uid = wp_booking_user_manually.id
                  WHERE wp_booking_join.cid = " . $_GET['calendar_id'] . "
                    AND wp_booking_join.eid = " . $_GET['event_id'] . "
                    AND wp_booking_join.status = 2";
    ?>
    
    <?php $result = $wpdb -> get_results( $sql ) or die(mysql_error()) ?>

    <div class="" style="margin-top: 20px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?php echo __( 'Manually Added Users' ) ?></h4>
            </div>
            <div class="panel-body"></div>
            <div class="feature-section">
                <table class="datatable">
                    <thead>
                            <tr>
                                <th><?php echo __('User Enrolled At')?></th>
                                <th><?php echo __('User Name')?></th>
                                <th><?php echo __('Phone Number')?></th>
                                <th><?php echo __('Calendar')?></th>
                                <th><?php echo __('Actions')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $row) {
                                ?>
                                <tr>
                                    <td><?php echo date('d F, Y', strtotime($row->join_date)) ?></td>
                                    <td><?php echo $row->name ?></td>
                                    <td><?php echo $row->phone ?></td>
                                    <td>
                                        <?php
                                        if ($row->cid > 0) {
                                            $scc = "SELECT * FROM wp_booking_cal_category WHERE id = $row->cid";
                                            $result_cc = $wpdb->get_results($scc) or die(mysql_error());
                                            echo stripcslashes( $result_cc[0]->title_en );
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn-danger btn btn-xs del-user" alt="<?php echo $row -> cid ?>|<?php echo $row -> eid ?>|<?php echo $row -> id ?>">
                                            <span class="fa fa-trash-o"></span> <?php echo __( 'Delete Class' ) ?>
                                        </a>
                                    </td>
                                </tr>

                                <?php
                            }
                            ?>
                        </tbody>
                    
                </table>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        jQuery('.del-user').on('click', function(e){
            
            if ( confirm( "<?php echo __( 'Are you sure you want to delete this?' ) ?>" )) {
                
                var btn_alt = jQuery(this).attr('alt');
                var btn_alt_array = btn_alt.split( '|' );
                var calendar_id = btn_alt_array[ 0 ];
                var event_id = btn_alt_array[ 1 ];
                var join_id = btn_alt_array[ 2 ];
                
                document.location = "booking/delete_from_manual_enrollment.php?calendar_id=" + calendar_id + "&event_id=" + event_id + "&join_id=" + join_id;
            }
        });

    });
</script>