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

    <h1><?php echo __( 'Manual Enrollment' ) ?></h1>

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
    
    <h2 class="nav-tab-wrapper">
        <a href="booking.php" class="nav-tab"><?php echo __('Booking List')?></a>
        <a href="brands.php" class="nav-tab "><?php echo __('Brands & Models')?></a>
        <a href="calendar.php" class="nav-tab"><?php echo __('Calendar')?></a>
    </h2>

    <!-- booking form starts here -->
    <div class="col-md-4" style="margin-top: 20px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?php // echo __( 'Create Calendar & Classes' ) ?></h4>
            </div>
            <div class="panel-body">

                <form id="cooking-class-form" method="post" action="<?php echo 'booking/booking_manually.php' ?>">
                    
                    <div class="row">
                        
                        <div class="input-group">
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-user"></span></div>
                                <input type="text" name="name" placeholder="<?php echo __( 'Name' ) ?>" required>
                            </div>
                        </div><br />

                        <div class="input-group">
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-phone"></span></div>
                                <input type="text" name="phone" placeholder="<?php echo __( 'Phone Number' ) ?>" required>
                            </div>
                        </div><br />

                    </div>                            

                    <div>
                        <div class="center-block">
                            <input type="hidden" name="lang" value="<?php echo $l ?>">
                            <input type="hidden" name="calendar_id" value="<?php echo $_GET[ 'calendar_id' ] ?>">
                            <input type="hidden" name="event_id" value="<?php echo $_GET[ 'event_id' ] ?>">
                            <button class="btn btn-primary btn-lg btn-block" type="submit"><span class="fa fa-save"></span> <?php echo __( 'Submit' ) ?></button>
                            <button class="btn btn-default btn-lg btn-block" type="reset"><span class="fa fa-chain-broken"></span> <?php echo __( 'Reset' ) ?></button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- booking form ends here -->

</div>