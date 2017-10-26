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

<!--[if lt IE 9]><script>document.createElement('audio');document.createElement('video');</script><![endif]-->
<div class="wrap ">

    <h1>Class Assignment</h1>
    <?php
    
        /*
         * Array
            (
                [base_url] => http://localhost/projects/class_cooking/
                [user_fullname] => Rumana Afrin
                [email] => rumana.afrin@yahoo.com
                [id] => 32
                [cid] => 6
                [serial] => 2147484848
                [status] => 1
                [lang] => en
            )
         */
    
        $sql = "SELECT * FROM wp_booking_cal_event WHERE cid = " . $_POST['cid'] . " AND status = 1 ORDER BY start_date DESC";
    
        $result = $wpdb->get_results($sql) or die(mysql_error());
    
    ?>
    
    <div class="row">
        <?php if (count( $result ) > 0 ){ ?>
            <form action="booking/assign_class.php" method="POST">
            <div class="changelog headline-feature">
                <div class="feature-section">
                    <table class="dataTable tbl-book-class">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?php echo __('Class Title')?></th>
                                <th><?php echo __('Class Description')?></th>
                                <th><?php echo __('Class Date')?></th>
                                <th><?php echo __('Class Time')?></th>
                            </tr>
                        </thead>
                        <tfoot id="tfoot-book-class" style="display: none;">
                            <tr>
                                <th colspan="5">
                                    <input type="hidden" value="<?php echo home_url('/') ?>" name="base_url" />
                                    <input type="hidden" value="<?php echo $_POST['fname'] . ' ' . $_POST['lname'] ?>" name="user_fullname" />
                                    <input type="hidden" value="<?php echo $_POST['email'] ?>" name="email" />
                                    <input type="hidden" value="<?php echo $_POST['id'] ?>" name="id" />
                                    <input type="hidden" value="<?php echo $_POST['cid'] ?>" name="cid" />
                                    <input type="hidden" value="<?php echo $_POST['serial'] ?>" name="serial" />
                                    <input type="hidden" value="<?php echo $_POST['status'] ?>" name="status" />
                                    <input type="hidden" value="<?php echo $_POST['lang'] ?>" name="lang" />
                                    <button class="btn btn-primary btn-sm" type="submit"><span class="fa fa-save"></span> <?php echo __('Book Class')?></button>
                                </th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($result as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <input type="radio" class="rdo-class" name="chosen-class" value="<?php echo $row -> id ?>" />
                                    </td>
                                    <td>
                                        <?php echo ( $l == 'en' ) ? $row -> title_en : $row -> title ?>
                                    </td>
                                    <td>
                                        <?php echo ( $l == 'en' ) ? $row -> desc_en : $row -> desc ?>
                                    </td>
                                    <td>
                                        <?php echo date( 'F d, Y', strtotime( $row -> start_date ) ) ?>
                                    </td>
                                    <td>
                                        <?php
                                            $class_starts_at = $row -> start_date . ' ' . $row -> start;
                                            $class_start_date = date( 'd-m-Y', strtotime( $class_starts_at ) );
                                            $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
                                            $class_end_time = date( 'h:i a', strtotime( $row -> end ) );
                                            echo $class_start_time . ' - ' . $class_end_time;
                                        ?>
                                    </td>
                                </tr>

                                <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="clear"></div>
            </form>    
            </div>
        <?php } ?>
    </div>
</div>