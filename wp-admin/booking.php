<?php
/**
 * About This Version administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */
session_start();
/** WordPress Administration Bootstrap */
require_once( dirname(__FILE__) . '/admin.php' );

wp_enqueue_style('wp-mediaelement');
wp_enqueue_script('wp-mediaelement');
wp_localize_script('mediaelement', '_wpmejsSettings', array(
    'pluginPath' => includes_url('js/mediaelement/', 'relative'),
    'pauseOtherPlayers' => ''
));

$title = __('Brands & Models');

list( $display_version ) = explode('-', $wp_version);

include( ABSPATH . 'wp-admin/admin-header.php' );
include( ABSPATH . 'wp-admin/booking-script.php' );

$l = ICL_LANGUAGE_CODE;
global $wpdb;

?>

  <!-- eheading -->
  <!-- split booking list into open and closed -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
<!--[if lt IE 9]><script>document.createElement('audio');document.createElement('video');</script><![endif]-->
<div class="wrap ">

    <h1>Booking</h1>
    <?php
    if ( isset( $_SESSION['msg'] ) ) {
        ?>
        <div class="alert alert-success">
            <?php if ($_SESSION['msg'] == 'success') {
                echo __('Added Successfully!');
            }?>
            <?php
            if ($_SESSION['msg'] == 'enabled') {
                echo __('The login information was successfully mailed to the user');
            }
            ?>
            <?php if ($_SESSION['msg'] == 'del') {
                echo __('Deleted Successfully!');
            }?>
            <?php if ($_SESSION['msg'] == 'disqualified') {
                echo __('User Disqualified Successfully!');
            }?>
        </div>

            <?php if ($_SESSION['msg'] == 'enabling_failed'): ?>
                <div class="alert alert-danger">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo __('Failed to enable the user!')?>
                </div>
            <?php elseif ($_SESSION['msg'] == 'disqualification_failed'): ?>    
                <div class="alert alert-danger">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo __('Failed to disqualify the user!')?>
                </div>
            <?php elseif ($_SESSION['msg'] == 'booking_failed'): ?>    
                <div class="alert alert-danger">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo __('Failed to book the class!')?>
                </div>
            <?php elseif ($_SESSION['msg'] == 'booking_success'): ?>    
                <div class="alert alert-success">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo __('Class booked successfully')?>
                </div>
            <?php endif ?>
        <?php
        unset( $_SESSION['msg'] );
    }
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="booking.php" class="nav-tab nav-tab-active"><?php echo __('Booking List')?></a>
        <a href="brands.php" class="nav-tab "><?php echo __('Brands & Models')?></a>
        <a href="calendar.php" class="nav-tab"><?php echo __('Calendar')?></a>
    </h2>
  
    <?php
    if ( isset($_GET['calendar_id']) && isset($_GET['event_id']) && ( $_GET['num_of_enrollments'] > 0 )  ) {
        /** to check the enrolled users in Calendar **/
        $sql = "SELECT wp_booking_join.create_time AS join_date, wp_booking_join.cid, wp_booking_join.lang,
                   wp_booking_user.fname, wp_booking_user.lname, wp_booking_user.phone, wp_booking_user.email,
                   wp_booking_user.bid, wp_booking_user.retail_outlet, wp_booking_user.delivery_date,
                   wp_booking_user.invoice_img, wp_booking_user.invitation_letter_img,
                   wp_booking_user.deadline, wp_booking_user.id, wp_booking_user.serial, wp_booking_user.status
            FROM wp_booking_join
            INNER JOIN wp_booking_user
            ON wp_booking_join.uid = wp_booking_user.id
            WHERE wp_booking_join.cid = " . $_GET['calendar_id'] . "
              AND wp_booking_join.eid = " . $_GET['event_id'] . "
              AND wp_booking_join.status = 1";
    } else {
        /** to list all the members **/
        $sql = "SELECT * FROM wp_booking_user ";
    }
    
    
    $result = $wpdb->get_results($sql) or die(mysql_error());
    
    ?>
    
    <?php if( isset( $_GET['num_of_enrollments'] ) && ( $_GET['num_of_enrollments'] == 0 ) ){ ?>
        <br /><br />
        <div class="alert alert-danger">
            <?php echo __('No result found!')?>
        </div>
    <?php } ?>
    
    
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#open">Open</a></li>
        <li><a data-toggle="tab" href="#closed">Closed</a></li>
        <li><a class="btn" href="http://delonghiacademy.com.hk/wp-admin/export_members.php">Export members</a></li>
      </ul>
  
    <!---------------------->
    <!-- for open class ---->
    <!---------------------->
    <div class="tab-pane fade in active" id="open" >
        <?php $index = 1 ?>
        <?php if (count( $result ) > 0 ){ ?>
            <div class="changelog headline-feature">
                <h2>LIST</h2>
                <div class="feature-section">
                    <table class="datatable">
                        <thead>
                            <tr>
                                <th><?php echo __('ID')?></th>
                                <th><?php echo __('Registered At')?></th>
                                <th><?php echo __('User Name')?></th>
                                <th><?php echo __('Contact Number')?></th>
                                <th><?php echo __('Email')?></th>
                                <th><?php echo __('Brand')?></th>
                                <th><?php echo __('Model')?></th>
                                <th><?php echo __('Retail Outlet')?></th>
                                <th><?php echo __('Delivery Date')?></th>
                                <th><?php echo __('Invoice Image')?></th>
                                <th><?php echo __('Invitation Letter')?></th>
                                <th><?php echo __('Class in English only')?></th>
                                <th><?php echo __('Status')?></th>
                                <th><?php echo __('Deadline')?></th>
                                <th><?php echo __('Calendar')?></th>
                                <th><?php echo __('Calendar Assignment')?></th>
                                <th><?php echo __('Class Enrolled')?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><?php echo __('ID')?></th>
                                <th><?php echo __('Registered At')?></th>
                                <th><?php echo __('User Name')?></th>
                                <th><?php echo __('Contact Number')?></th>
                                <th><?php echo __('Email')?></th>
                                <th><?php echo __('Brand')?></th>
                                <th><?php echo __('Model')?></th>
                                <th><?php echo __('Retail Outlet')?></th>
                                <th><?php echo __('Delivery Date')?></th>
                                <th><?php echo __('Invoice Image')?></th>
                                <th><?php echo __('Invitation Letter')?></th>
                                <th><?php echo __('Class in English only')?></th>
                                <th><?php echo __('Status')?></th>
                                <th><?php echo __('Deadline')?></th>
                                <th><?php echo __('Calendar')?></th>
                                <th><?php echo __('Calendar Assignment')?></th>
                                <th><?php echo __('Class Enrolled')?></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($result as $row) {
                            
                            $open_class_sql = "SELECT wp_booking_cal_event.title_en, wp_booking_cal_event.start_date, wp_booking_cal_event.start, wp_booking_cal_event.end "
                                                    . "FROM wp_booking_cal_event "
                                                    . "RIGHT JOIN wp_booking_join "
                                                    . "ON wp_booking_join.eid = wp_booking_cal_event.id "
                                                    . "WHERE wp_booking_join.uid = " . $row->id . " "
                                                    . "AND wp_booking_cal_event.start_date >= CURDATE()";
                            
                            $open_result = $wpdb->get_row($open_class_sql);
                                         
                            $not_yet_reg_class_sql = "Select * from wp_booking_join WHERE " . $row->id . " NOT IN (SELECT uid FROM wp_booking_join)";
                            
                            $not_yet_reg_result = $wpdb->get_row($not_yet_reg_class_sql);
                            
                            if(count($open_result) > 0 or count($not_yet_reg_result) > 0){
                                       
                            ?>
                                <tr>
                                    <td><?php echo $index?></td>
                                    <td><?php echo $row->create_time?></td>
                                    <td><?php echo $row->fname . '&nbsp;' . $row->lname?></td>
                                    <td><?php echo $row->phone?></td>
                                    <td><?php echo $row->email?></td>
                                    <td>
                                        <?php
                                        $sb = "SELECT * FROM wp_booking_brand WHERE id = $row->bid";
                                        $result_s = $wpdb->get_results($sb) or die(mysql_error());
                                        echo $result_s[0]->title_en;
                                        ?>
                                    </td>
                                    <td>
                                        <?php $model_id = $row->model?>
                                        <?php if (!empty($model_id) && ( $model_id > 0 )):?>
                                            <?php $query_model = "SELECT * FROM wp_booking_model WHERE id = $row->model"?>
                                            <?php $result_model = $wpdb->get_results($query_model) or die(mysql_error())?>
                                            <?php echo $result_model[0]->title?>
        <?php endif?>
                                    </td>
                                    <td><?php echo $row->retail_outlet?></td>
                                    <td>
                                        <?php if (!empty($row->delivery_date)):?>
                                            <?php echo date('d F, Y', strtotime($row->delivery_date))?>
        <?php endif?>
                                    </td>
                                    <td>
                                        <a href="../invoice/<?php echo $row->invoice_img?>" target="_blank">
                                            <img src="../invoice/<?php echo $invoice_image_dir?>/<?php echo $row->invoice_img?>" width="80" />
                                        </a>
                                    </td>
                                    <td>
                                        <a href="../invitation_letter/<?php echo $row->invitation_letter_img?>" target="_blank">
                                            <img src="../invitation_letter/<?php echo $row->invitation_letter_img?>" width="80" />
                                        </a>
                                    </td>
                                    <td>
                                    <?php if ($row->cooking_class_in_english == '1'){ ?>
                                        <?php echo __('Yes') ?>
                                    <?php } else { ?>
                                        <?php echo __('No') ?>
                                    <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($row->status == 0):?>
                                            <span class="label label-default"><?php echo __('Disabled')?></span>
                                        <?php elseif ($row->status == 4):?>
                                            <span class="label label-danger"><?php echo __('Disqualified')?></span>
                                        <?php else:?>
                                            <span class="label label-success"><?php echo __('Enabled')?></span>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row->deadline) && ( $row->deadline != '0000-00-00' )):?>
                                            <?php echo date('d F, Y', strtotime($row->deadline))?>
                                        <?php endif ?>
                                    </td>
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
                                        <form action="booking/enabled.php" method="POST">
                                            Calendar:
                                            <select name="cid" required >
                                                <option value=""></option>
                                                <?php
                                                $sc = "SELECT * FROM wp_booking_cal_category";
                                                $result_c = $wpdb->get_results($sc) or die(mysql_error());
                                                foreach ($result_c as $rc) {
                                                    ?>
                                                    <option value="<?php echo $rc->id?>"<?php if ($row->cid == $rc->id) {
                                                echo ' selected="selected"';
                                            }?>><?php echo stripcslashes( $rc->title_en ) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" value="<?php echo home_url('/')?>" name="base_url" />
                                            <input type="hidden" value="<?php echo $row->fname . ' ' . $row->lname?>" name="user_fullname" />
                                            <input type="hidden" value="<?php echo $row->email?>" name="email" />
                                            <input type="hidden" value="<?php echo $row->id?>" name="id" />
                                            <input type="hidden" value="<?php echo $row->serial?>" name="serial" />
                                            <input type="hidden" value="<?php echo $row->status?>" name="status" />
                                            <input type="hidden" value="<?php echo $row->lang?>" name="lang" /><br /><br />
                                            <?php if ( $row->status == 0 ): ?>
                                                <button class="btn btn-success btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Select Calendar to Activate')?></button>
                                                <a href="booking/disqualify.php?user_id=<?php echo $row->id?>" class="btn btn-danger btn-sm" type="submit">
                                                    <span class="fa fa-save"></span> <?php echo __('Disqualify')?>
                                                </a>
                                            <?php elseif ( $row->status == 1 ): ?>
                                                <button class="btn btn-primary btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Select Different Calendar')?></button>
                                            <?php elseif ( $row->status > 1 ): ?>
                                                <button class="btn btn-primary btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Reactivate by Selecting Calendar')?></button>
        <?php endif?>
                    <!--                    <input type="submit" value="Enabled" />-->
                                        </form>
                                    </td>
                                    <td>
                                        <?php
                                            $booking_sql = "SELECT wp_booking_cal_event.title_en, wp_booking_cal_event.start_date, wp_booking_cal_event.start, wp_booking_cal_event.end "
                                                    . "FROM wp_booking_cal_event "
                                                    . "RIGHT JOIN wp_booking_join "
                                                    . "ON wp_booking_join.eid = wp_booking_cal_event.id "
                                                    . "WHERE wp_booking_join.uid = " . $row->id . " ";
                                                    //. "AND wp_booking_cal_event.start_date >= CURDATE()";
                                            
                                            $user_info = $wpdb -> get_row( $booking_sql );
                                            
                                            if (count( $user_info ) > 0 ) {
                                                
                                                $class_title = stripcslashes( $user_info -> title_en );
                                                
                                                $class_starts_at = $user_info -> start_date . ' ' . $user_info -> start;
                                                $class_ends_at = $user_info -> start_date . ' ' . $user_info -> end;
                                                $class_start_date = date( 'F d, Y', strtotime( $class_starts_at ) );
                                                $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
                                                $class_end_time = date( 'h:i a', strtotime( $class_ends_at ) );
                                                
                                                echo $class_title . '<br />';
                                                echo '( ' . $class_start_date . ': from ' . $class_start_time . ' to ' . $class_end_time . ' )';
                                            }
                                        ?>
                                        <?php if ( $row->status == 1 ): ?>
                                            <form action="assign_class.php" method="POST">
                                                <input type="hidden" value="<?php echo home_url('/') ?>" name="base_url" />
                                                <input type="hidden" value="<?php echo $row->fname . ' ' . $row->lname ?>" name="user_fullname" />
                                                <input type="hidden" value="<?php echo $row->email ?>" name="email" />
                                                <input type="hidden" value="<?php echo $row->id ?>" name="id" />
                                                <input type="hidden" value="<?php echo $row->cid ?>" name="cid" />
                                                <input type="hidden" value="<?php echo $row->serial ?>" name="serial" />
                                                <input type="hidden" value="<?php echo $row->status ?>" name="status" />
                                                <input type="hidden" value="<?php echo $row->lang ?>" name="lang" />
                                                <button class="btn btn-default btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Admin Assign') ?></button>
                                            </form>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <?php
                                $index ++;
                                    } /** end if count **/
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="clear"></div>

                <?php if ( isset( $_GET[ 'calendar_id' ] ) && isset( $_GET[ 'event_id' ] ) ): ?>
                    <center>
                        <a href="booking.php" class="btn btn-warning btn-lg"><span class="fa fa-users"></span> <?php echo __( 'See Complete List' ) ?></a>
                    </center>
                <?php endif ?>

            </div>
        <?php } ?>
        
        <?php if( $index == 1 ) { ?>
            <div class="alert alert-danger">
                <?php echo __('No result found!')?>
            </div>
        <?php } ?>
    </div>
    
    <!---------------------->
    <!-- for closed class -->
    <!---------------------->
    <div id="closed" class="tab-pane fade">
        <?php $index = 1 ?>
        <?php if (count( $result ) > 0 ){ ?>
            <div class="changelog headline-feature">
                <h2>LIST</h2>
                <div class="feature-section">
                    <table class="datatable">
                        <thead>
                            <tr>
                                <th><?php echo __('ID')?></th>
                                <th><?php echo __('Registered At')?></th>
                                <th><?php echo __('User Name')?></th>
                                <th><?php echo __('Contact Number')?></th>
                                <th><?php echo __('Email')?></th>
                                <th><?php echo __('Brand')?></th>
                                <th><?php echo __('Model')?></th>
                                <th><?php echo __('Retail Outlet')?></th>
                                <th><?php echo __('Delivery Date')?></th>
                                <th><?php echo __('Invoice Image')?></th>
                                <th><?php echo __('Invitation Letter')?></th>
                                <th><?php echo __('Class in English only')?></th>
                                <th><?php echo __('Status')?></th>
                                <th><?php echo __('Deadline')?></th>
                                <th><?php echo __('Calendar')?></th>
                                <th><?php echo __('Calendar Assignment')?></th>
                                <th><?php echo __('Class Enrolled')?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><?php echo __('ID')?></th>
                                <th><?php echo __('Registered At')?></th>
                                <th><?php echo __('User Name')?></th>
                                <th><?php echo __('Contact Number')?></th>
                                <th><?php echo __('Email')?></th>
                                <th><?php echo __('Brand')?></th>
                                <th><?php echo __('Model')?></th>
                                <th><?php echo __('Retail Outlet')?></th>
                                <th><?php echo __('Delivery Date')?></th>
                                <th><?php echo __('Invoice Image')?></th>
                                <th><?php echo __('Invitation Letter')?></th>
                                <th><?php echo __('Class in English only')?></th>
                                <th><?php echo __('Status')?></th>
                                <th><?php echo __('Deadline')?></th>
                                <th><?php echo __('Calendar')?></th>
                                <th><?php echo __('Calendar Assignment')?></th>
                                <th><?php echo __('Class Enrolled')?></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($result as $row) {
                            
                            $closed_class_sql = "SELECT wp_booking_cal_event.title_en, wp_booking_cal_event.start_date, wp_booking_cal_event.start, wp_booking_cal_event.end "
                                                    . "FROM wp_booking_cal_event "
                                                    . "RIGHT JOIN wp_booking_join "
                                                    . "ON wp_booking_join.eid = wp_booking_cal_event.id "
                                                    . "WHERE wp_booking_join.uid = " . $row->id . " "
                                                    . "AND wp_booking_cal_event.start_date < CURDATE()";
                            
                            $closed_result = $wpdb->get_row($closed_class_sql);
                            
                            if(count($closed_result) > 0 ){
                                       
                            ?>
                                <tr>
                                    <td><?php echo $index?></td>
                                    <td><?php echo $row->create_time?></td>
                                    <td><?php echo $row->fname . '&nbsp;' . $row->lname?></td>
                                    <td><?php echo $row->phone?></td>
                                    <td><?php echo $row->email?></td>
                                    <td>
                                        <?php
                                        $sb = "SELECT * FROM wp_booking_brand WHERE id = $row->bid";
                                        $result_s = $wpdb->get_results($sb) or die(mysql_error());
                                        echo $result_s[0]->title_en;
                                        ?>
                                    </td>
                                    <td>
                                        <?php $model_id = $row->model?>
                                        <?php if (!empty($model_id) && ( $model_id > 0 )):?>
                                            <?php $query_model = "SELECT * FROM wp_booking_model WHERE id = $row->model"?>
                                            <?php $result_model = $wpdb->get_results($query_model) or die(mysql_error())?>
                                            <?php echo $result_model[0]->title?>
        <?php endif?>
                                    </td>
                                    <td><?php echo $row->retail_outlet?></td>
                                    <td>
                                        <?php if (!empty($row->delivery_date)):?>
                                            <?php echo date('d F, Y', strtotime($row->delivery_date))?>
        <?php endif?>
                                    </td>
                                    <td>
                                        <a href="../invoice/<?php echo $row->invoice_img?>" target="_blank">
                                            <img src="../invoice/<?php echo $invoice_image_dir?>/<?php echo $row->invoice_img?>" width="80" />
                                        </a>
                                    </td>
                                    <td>
                                        <a href="../invitation_letter/<?php echo $row->invitation_letter_img?>" target="_blank">
                                            <img src="../invitation_letter/<?php echo $row->invitation_letter_img?>" width="80" />
                                        </a>
                                    </td>
                                    <td>
                                    <?php if ($row->cooking_class_in_english == '1'){ ?>
                                        <?php echo __('Yes') ?>
                                    <?php } else { ?>
                                        <?php echo __('No') ?>
                                    <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($row->status == 0):?>
                                            <span class="label label-default"><?php echo __('Disabled')?></span>
                                        <?php elseif ($row->status == 4):?>
                                            <span class="label label-danger"><?php echo __('Disqualified')?></span>
                                        <?php else:?>
                                            <span class="label label-success"><?php echo __('Enabled')?></span>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row->deadline) && ( $row->deadline != '0000-00-00' )):?>
                                            <?php echo date('d F, Y', strtotime($row->deadline))?>
                                        <?php endif ?>
                                    </td>
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
                                        <form action="booking/enabled.php" method="POST">
                                            Calendar:
                                            <select name="cid" required >
                                                <option value=""></option>
                                                <?php
                                                $sc = "SELECT * FROM wp_booking_cal_category";
                                                $result_c = $wpdb->get_results($sc) or die(mysql_error());
                                                foreach ($result_c as $rc) {
                                                    ?>
                                                    <option value="<?php echo $rc->id?>"<?php if ($row->cid == $rc->id) {
                                                echo ' selected="selected"';
                                            }?>><?php echo stripcslashes( $rc->title_en ) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" value="<?php echo home_url('/')?>" name="base_url" />
                                            <input type="hidden" value="<?php echo $row->fname . ' ' . $row->lname?>" name="user_fullname" />
                                            <input type="hidden" value="<?php echo $row->email?>" name="email" />
                                            <input type="hidden" value="<?php echo $row->id?>" name="id" />
                                            <input type="hidden" value="<?php echo $row->serial?>" name="serial" />
                                            <input type="hidden" value="<?php echo $row->status?>" name="status" />
                                            <input type="hidden" value="<?php echo $row->lang?>" name="lang" /><br /><br />
                                            <?php if ( $row->status == 0 ): ?>
                                                <button class="btn btn-success btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Select Calendar to Activate')?></button>
                                                <a href="booking/disqualify.php?user_id=<?php echo $row->id?>" class="btn btn-danger btn-sm" type="submit">
                                                    <span class="fa fa-save"></span> <?php echo __('Disqualify')?>
                                                </a>
                                            <?php elseif ( $row->status == 1 ): ?>
                                                <button class="btn btn-primary btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Select Different Calendar')?></button>
                                            <?php elseif ( $row->status > 1 ): ?>
                                                <button class="btn btn-primary btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Reactivate by Selecting Calendar')?></button>
        <?php endif?>
                    <!--                    <input type="submit" value="Enabled" />-->
                                        </form>
                                    </td>
                                    <td>
                                        <?php
                                            $booking_sql = "SELECT wp_booking_cal_event.title_en, wp_booking_cal_event.start_date, wp_booking_cal_event.start, wp_booking_cal_event.end "
                                                    . "FROM wp_booking_cal_event "
                                                    . "RIGHT JOIN wp_booking_join "
                                                    . "ON wp_booking_join.eid = wp_booking_cal_event.id "
                                                    . "WHERE wp_booking_join.uid = " . $row->id . " ";
                                                    //. "AND wp_booking_cal_event.start_date >= CURDATE()";
                                            
                                            $user_info = $wpdb -> get_row( $booking_sql );
                                            
                                            if (count( $user_info ) > 0 ) {
                                                
                                                $class_title = stripcslashes( $user_info -> title_en );
                                                
                                                $class_starts_at = $user_info -> start_date . ' ' . $user_info -> start;
                                                $class_ends_at = $user_info -> start_date . ' ' . $user_info -> end;
                                                $class_start_date = date( 'F d, Y', strtotime( $class_starts_at ) );
                                                $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
                                                $class_end_time = date( 'h:i a', strtotime( $class_ends_at ) );
                                                
                                                echo $class_title . '<br />';
                                                echo '( ' . $class_start_date . ': from ' . $class_start_time . ' to ' . $class_end_time . ' )';
                                            }
                                        ?>
                                        <?php if ( $row->status == 1 ): ?>
                                            <form action="assign_class.php" method="POST">
                                                <input type="hidden" value="<?php echo home_url('/') ?>" name="base_url" />
                                                <input type="hidden" value="<?php echo $row->fname . ' ' . $row->lname ?>" name="user_fullname" />
                                                <input type="hidden" value="<?php echo $row->email ?>" name="email" />
                                                <input type="hidden" value="<?php echo $row->id ?>" name="id" />
                                                <input type="hidden" value="<?php echo $row->cid ?>" name="cid" />
                                                <input type="hidden" value="<?php echo $row->serial ?>" name="serial" />
                                                <input type="hidden" value="<?php echo $row->status ?>" name="status" />
                                                <input type="hidden" value="<?php echo $row->lang ?>" name="lang" />
                                                <button class="btn btn-default btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Admin Assign') ?></button>
                                            </form>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <?php
                                $index ++;
                                    } /** end if count **/
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="clear"></div>

                <?php if ( isset( $_GET[ 'calendar_id' ] ) && isset( $_GET[ 'event_id' ] ) ): ?>
                    <center>
                        <a href="booking.php" class="btn btn-warning btn-lg"><span class="fa fa-users"></span> <?php echo __( 'See Complete List' ) ?></a>
                    </center>
                <?php endif ?>

            </div>
        <?php } ?>
        
        <?php if( $index == 1 ) { ?>
            <div class="alert alert-danger">
                <?php echo __('No result found!')?>
            </div>
        <?php } ?>
    </div>
</div>

  