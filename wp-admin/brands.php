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

global $wpdb;

$update_model_order_url = 'http://' . $_SERVER['SERVER_NAME'] . '/wp-admin/booking/update_model_order.php';
?>

<!--[if lt IE 9]><script>document.createElement('audio');document.createElement('video');</script><![endif]-->
<div class="wrap ">

<h1>Booking</h1>
<?php
if($_SESSION['msg'])
{
?>  
<div class="alert alert-info">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?php if($_SESSION['msg']=='success'){ echo __( 'Added Successfully!' ); } ?>
    <?php if($_SESSION['msg']=='del'){ echo __( 'Deleted Successfully!' ); } ?>
</div>
<?php
    unset($_SESSION['msg']);
}
?>
<!--
<div class="about-text"><?php printf( __( 'Thank you for updating! WordPress %s helps you focus on your writing, and the new default theme lets you show it off in style.' ), $display_version ); ?></div>
-->
<h2 class="nav-tab-wrapper">
	<a href="booking.php" class="nav-tab">
		Booking List
	</a>
    <a href="brands.php" class="nav-tab nav-tab-active">
		Brands & Models
	</a>
    <a href="calendar.php" class="nav-tab">
		Calendar
	</a>
</h2>
<?php $sql = "SELECT * FROM wp_booking_brand" ?>
<?php $result = $wpdb->get_results($sql) or die(mysql_error()) ?>

<div class="row">
<h2>FORM</h2>
    <div class=" col-md-6">
    	<h3>Brand</h3>
    	<p>
        <form action="booking/brand_add.php" method="POST">
            <div class="form-group">
                <label>Brand Name (en)</label>
                <input class="form-control" name="title_en" value="" required="" />
            </div>
            <div class="form-group">
                <label>Brand Name (中)</label>
                <input class="form-control" name="title" value="" required="" />
            </div>
            <input name="status" value="1" type="hidden" />
            <button type="submit" class="btn btn-default">ADD</button>        
        </form>
     	</p>
    </div>
    <div class=" col-md-6">
    	<h3>Model Number</h3>
    	<p>
        <form action="booking/model_add.php" method="POST">
            <div class="form-group">
                <label>Brand</label>
                <select class="form-control" name="bid">
                    <?php foreach( $result as $row): ?>
                    
                        <?php if ( isset( $_GET[ 'new_brand_id' ] ) && ( $row -> id == $_GET[ 'new_brand_id' ] ) ): ?>
                            <option value="<?php echo $row->id ?>" selected="selected"><?php echo $row->title_en ?></option>
                        <?php else: ?>
                            <option value="<?php echo $row->id ?>"><?php echo $row->title_en ?></option>
                        <?php endif ?>

                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label>Model Number</label>
                <input class="form-control" name="title" value="" required="" />
            </div>
            <input name="status" value="1" type="hidden" />
            <input name="update_model_order_url" id="update_model_order_url" value="<?php echo $update_model_order_url ?>" type="hidden" />
            <button type="submit" class="btn btn-default">ADD</button>        
        </form>
     	</p>
    </div>
</div>
<hr />
<div class="changelog headline-feature">
	<h2>LIST</h2>
	<div class="feature-section">
        <table class="datatable" id="brands-n-models">
            <thead>
                <tr>
                    <th>Brand Name (en)</th>
                    <th>Brand Name (中)</th>
                    <th>Model</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Brand Name (en)</th>
                    <th>Brand Name (中)</th>
                    <th>Model</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
                <?php               
                
                foreach( $result as $row) {
                ?>
                <tr>
                    <td><b><?php echo $row->title_en ?></b></td>
                    <td><b><?php echo $row->title ?></b></td>
                    <td></td>
                    <td><button onclick="del_brand(<?php echo $row->id; ?>)" class="btn btn-xs btn-danger">Delete Brand</button></td>
                </tr>
                <?php
                    $sql_m = "SELECT * FROM wp_booking_model WHERE bid = $row->id";
                    $result_m = $wpdb->get_results($sql_m) or die(mysql_error());                    
                    foreach( $result_m as $row_m) 
                    {
                ?>
                <tr>
                    <td><?php echo $row->title_en ?></td>
                    <td><?php echo $row->title ?></td>
                    <td>
                        <b><?php echo $row_m->title ?></b>
                        <?php if ( count( $result_m ) > 1 ): ?>
                            &nbsp;&nbsp;
                            <input type="number" name="sort_by_<?php echo $row_m->id ?>" id="sort_by_<?php echo $row_m->id ?>" value="<?php echo $row_m->sort_by ?>" class="sort_by" />
                            &nbsp;
                            <a href="javascript:void(0);" class="update_model_order" id="update_model_order_<?php echo $row_m->id ?>" style="display:none;">update</a>
                            &nbsp;&nbsp;
                            <span id="update_status_<?php echo $row_m->id ?>"></span>
                        <?php endif ?>
                    </td>
                    <td><button onclick="del_model(<?php echo $row_m->id; ?>)" class="btn btn-xs btn-info ">Delete Model</button></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

	<div class="clear"></div>
</div>

</div>