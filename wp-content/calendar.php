<?php
/* Template Name: Calendar */
get_header(); 
$l = ICL_LANGUAGE_CODE;
session_start();

$ajax_url = 'http://' . $_SERVER['SERVER_NAME'] . '/wp-admin/admin-ajax.php';

?>
<style>
#cal_table{
    color:#707070;
    margin-bottom:0px;
}

#cal_table .week_text{
    font-weight: 900;
}

#cal_table .week_text td {
  background: #408e1b none repeat scroll 0 0;
  border-color: #bbbbbb;
  color: white;
  font-weight: 900;
  padding: 5px 0;
}

.day_title{
    background-color:#f2f2f2;
    padding:5px;
    font-weight: 900;
}

.cal_content{
    min-height:80px;
    background-color:#fcfcfc;
}

.cal_sel_label {
  line-height: 32px;
}

.cal_border {
  border: 1px solid #e2e2e2;
  padding: 10px;
  box-shadow: 0px 2px  3px #e2e2e2 ;
}

.cal_sel{
    padding:20px 0;
    border-bottom:0;
}

.op1{
    opacity: 1!important;
}

.mb15{
    margin-bottom:15px;
}

.bm0{
    margin-bottom:0px;
}

.today {
  background: #408e1b none repeat scroll 0 0 !important;
  color: white !important;
}

.event_link{
    padding:2px 10px;
    margin:5px;
}


</style>
    <div id="main" class="list-news <?php axiom_the_page_sidebar_pos($post->ID) ?>">
        <div class="wrapper fold clearfix">

            <?php

                if ( isset( $_GET[md5('uid')] ) && isset( $_GET[md5('cat')] ) ) {

                    $uid = $_GET[md5('uid')];
                    $cat = $_GET[md5('cat')];

                    $_SESSION['cat'] = $cat;
                    $_SESSION['uid'] = $uid;

                } else {

                    $uid = $_SESSION['uid'];
                    $cat = $_SESSION['cat'];
                }

                global $wpdb;

		if ( !isset( $uid ) ) {

                    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
                    $query_str = parse_url($url, PHP_URL_QUERY);
                    parse_str($query_str, $query_params);
                    
                    if ( isset( $query_params[ 'lang' ] ) ) {
                        
                        $lang_param_array = explode( "?", $query_params[ 'lang' ] );
                        $cat_string = $lang_param_array[ 1 ];
                        $cat_array = explode( "=", $cat_string );
                        
                        $cat = $cat_array[ 1 ];
                        
                        foreach ( $query_params as $key => $value ) {
                            if ( $key != 'lang' )
                                $uid = $value;
                        }
                        
                        $_SESSION['cat'] = $cat;
                    	$_SESSION['uid'] = $uid;
                        
                    }// end sub-if
		}// end main if 

                $user_data = $wpdb -> get_row( "SELECT * FROM wp_booking_user WHERE id = " . $uid );
                $user_status = $user_data -> status;

                if ( $user_status == 2 ) { //already booked a class

                    $query_event_id = $wpdb -> get_row( "SELECT eid FROM wp_booking_join WHERE uid = " . $uid . " AND cid = " . $cat );
                    $event_id = $query_event_id -> eid;

                    $query_class_data = $wpdb -> get_row( "SELECT * FROM wp_booking_cal_event WHERE id = " . $event_id );

                    $class_title = ( $l == 'en' ) ? $query_class_data -> title_en : $query_class_data -> title;
                    $class_desc = ( $l == 'en' ) ? $query_class_data -> desc_en : $query_class_data -> desc;

                    $class_title = stripcslashes( $class_title );
                    $class_desc = stripcslashes( $class_desc );

                    $class_starts_at = $query_class_data -> start_date . ' ' . $query_class_data -> start;
                    $class_ends_at = $query_class_data -> start_date . ' ' . $query_class_data -> end;
                    $class_start_date = date( 'F d, Y', strtotime( $class_starts_at ) );
                    $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
                    $class_end_time = date( 'h:i a', strtotime( $class_ends_at ) );
                    ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><?php echo __( 'Class Joined' ) ?>:&nbsp;<strong><?php echo stripcslashes( $class_title ) ?></strong></div>
                            <div class="panel-body">
                                <dl>
                                    <dt><?php echo stripcslashes( $class_desc ) ?></dt>
                                    <dd><i><?php echo $class_start_date ?></i>&nbsp;&nbsp;<u><?php echo $class_start_time ?>&nbsp;-&nbsp;<?php echo $class_end_time ?></u></dd>
                                </dl>
                            </div>
                        </div>
                    <?php
                } else {
            ?>

            <div class="alert alert-success">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                <p>
                    <?php echo __( 'You have logged in successfully.' ) ?><br />
                    <?php echo __( 'Please select one class from the calendar below. You can only choose one class with this login.' ) ?>
                </p>
            </div>

            <section id="calendar-section"><!-- id="primary" >-->
                <div class="content" role="main"  >

                    <div class="col-md-4 mb15">
                        <a class="btn btn-success form-control text-left disabled op1"><span class="dashicons dashicons-calendar-alt"></span> <?php echo __( 'Cooking Class' ) ?></a>
                    </div>
                    <?php
                    if(isset($_GET[md5('cat')]))
                    {
                    $_SESSION['cat'] = $cat;
                    $_SESSION['uid'] = $uid;
                    }
                    $sql = "SELECT * FROM wp_booking_cal_category WHERE id = ".$_SESSION['cat'];
                    $result = $wpdb->get_results($sql) or die(mysql_error());

                    $class_name = ( $l == 'en' ) ? $result[0] -> title_en : $result[0] -> title;
                    ?>
                    <div class="col-md-4 col-md-offset-4 mb15">
                        <a class="btn btn-info form-control disabled op1"><span class="dashicons dashicons-tag"></span> <?php echo stripcslashes( $class_name ) ?></a>
                    </div>

                    <div class="clearfix "></div>

                    <?php

                    //<-------GETæ–¹æ³•æäº¤è®Šæ›´æœˆä»½,å¹´ä»½;é–‹å§‹-------->

                    if($_GET['y'] == "") {
                        $_GET['y']=date("Y");
                    }

                    if($_GET['month'] == "") {
                        $_GET['month']=date("n");
                    }

                    $month=$_GET['month'];
                    $year=$_GET['y'];

                    //<-------GETæ–¹æ³•æäº¤è®Šæ›´æœˆä»½,å¹´ä»½;ç»“æŸ-------->



                    if($year<1971) {//å¹´åº¦æœ€å°‘åˆ°1971å¹´ï¼Œå°æ–¼1971å¹´ï¼Œå‰‡éœ€å›žåˆ°ä»Šå¹´çš„æ—¥æ›†

                        echo "<p>å·²è‡³å°¾ç«¯ï¼Œè«‹å›žåŽŸé é¢</p>";
                        echo "<a href=$_SERVER[PHP_SELF]>å›žåŽŸé é¢</a>"; //$_SERVER[PHP_SELF]ç‚ºåŸ·è¡Œä¼ºæœå™¨é å®šè®Šæ•¸ï¼Œç•¶å‰æ­£åœ¨åŸ·è¡Œè…³æœ¬çš„æ–‡ä»¶åã€‚
                        exit();
                    }

                    ?>

                    <div class="col-md-12">

                        <div class="cal_border cal_sel ">

                            <div class="col-md-4">

                            <?php

                            //<-------æœˆä»½è¶…å‡º1è‡³12çš„è™•ç†;é–‹å§‹------->

                            if($month<1)
                            {
                                $month=12;
                                $year-=1;
                            }

                            if($month>12)
                            {
                                $month=1;

                                $year+=1;

                            }

                            //<-------æœˆä»½è¶…å‡º1è‡³12çš„è™•ç†;ç»“æŸ------->



                            //<---------ä¸Šä¸€å¹´,ä¸‹ä¸€å¹´,ä¸Šæœˆ,ä¸‹æœˆ;é–‹å§‹--------->



                            //echo "<a href=$_SERVER[PHP_SELF]?year=".($year-1)."& month=".$month.">&lt;&lt;</a>".$year."<a href=$_SERVER[PHP_SELF]?year=".($year+1)."&month=".$month.">&gt;&gt;</a>"; //ä¸Šä¸‹å¹´

                            ?>



                                <label class="col-md-4 cal_sel_label" ><?php echo __( 'Year' ) ?></label>

                                <div class="col-md-8">

                                    <select onchange="window.location.href = this.value" class="form-control bm0">

                                    <?php

                                    $start_year = $year - date ( 'Y' );

                                    $before_year = $year - $start_year;

                                    for($m=0;$m<=$start_year;$m++) {
                                    ?>
                                        <option value="?<?php echo "&month=".$month."&y=".($before_year+$m)."&cat=$cat"; ?>" <?php echo ($before_year + $m==$year)?('selected'):(''); ?>><?php ; echo $before_year + $m; ?></option>
                                    <?php
                                    }
                                    ?>

                                    <?php

                                    $after_year = $year + 1;

                                    for($m=0;$m<=5;$m++) {
                                    ?>
                                        <option value="?<?php echo "&month=".$month."&y=".($after_year+$m)."&cat=$cat"; ?>"><?php ; echo $after_year + $m; ?></option>
                                    <?php
                                    }
                                    ?>

                                    </select>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <label class="col-md-4 cal_sel_label" ><?php echo __( 'Month' ) ?></label>

                                <div class="col-md-8">

                                    <select onchange="window.location.href = this.value" class="form-control bm0">

                                    <?php

                                    $month_names = array( '1' => ( $l == 'en' ) ? 'January' : '一月',
                                                          '2' => ( $l == 'en' ) ? 'February' : '二月',
                                                          '3' => ( $l == 'en' ) ? 'March' : '三月',
                                                          '4' => ( $l == 'en' ) ? 'April' : '四月',
                                                          '5' => ( $l == 'en' ) ? 'May' : '五月',
                                                          '6' => ( $l == 'en' ) ? 'June' : '六月',
                                                          '7' => ( $l == 'en' ) ? 'July' : '七月',
                                                          '8' => ( $l == 'en' ) ? 'August' : '八月',
                                                          '9' => ( $l == 'en' ) ? 'September' : '九月',
                                                          '10' => ( $l == 'en' ) ? 'October' : '十月',
                                                          '11' => ( $l == 'en' ) ? 'November' : '十一月',
                                                          '12' => ( $l == 'en' ) ? 'December' : '十二月' );

                                    for($m=1;$m<=12;$m++)

                                    {

                                    ?>

                                        <option value="?<?php echo "month=".($m)."&y=".$year."&cat=".$cat; ?>" <?php echo ($m ==$month)?('selected'):(''); ?>><?php echo $month_names[ $m ] ?></option>

                                    <?php

                                    }

                                    ?>

                                    </select>

                                </div>

                            </div>

                            <div class="clearfix"></div>

                        </div>



                        <div class="text-center cal_border mb10">

                        <table id="cal_table" class="table table-bordered " >

                        <tr align=center class="week_text">

                        <?php

                        echo "<td class='red_text'>" . __( 'Sunday' ) . "</td>"
                                . "<td>" . __( 'Monday' ) . "</td>"
                                . "<td>" . __( 'Tuesday' ) . "</td>"
                                . "<td>" . __( 'Wednesday' ) . "</td>"
                                . "<td>" . __( 'Thursday' ) . "</td>"
                                . "<td>" . __( 'Friday' ) . "</td>"
                                . "<td>" . __( 'Saturday' ) . "</td>";

                        echo "</tr>";

                        echo "<tr>";

                        $d=date("d");

                        $FirstDay=date("w",mktime(0,0,0,$month,1,$year));//å–å¾—ä»»ä½•ä¸€å€‹æœˆçš„ä¸€è™Ÿæ˜¯æ˜ŸæœŸå¹¾ï¼Œä¾†è¨ˆè‡ªä¸€è™Ÿå¾žç¬¬å¹¾æ ¼é–‹å§‹ã€‚

                        $bgtoday=date("d");

                        function font_color($month,$today,$year)//è¨ˆç®—æ˜ŸæœŸå¤©çš„å­—é«”é¡è‰²ã€‚

                        {
                            $FontColor="#707070";

                            return $FontColor;
                        }

                        function bgcolor($month,$bgtoday,$today_i,$year)//è¨ˆç®—ç•¶æ—¥çš„èƒŒæ™¯é¡è‰²ã€‚
                        {

                            $show_today=date("d-m-Y",mktime(0,0,0,$month,$today_i,$year));
                            $sys_today=date("d-m-Y",mktime(0,0,0,$month,$bgtoday,$year));

                            $curr_day = date('d-m-Y');
                            $bgcolor = ($show_today == $curr_day) ? "today" : "";

                            return $bgcolor;

                        }

                        function font_style($month,$today,$year) {//è¨ˆç®—æ˜ŸæœŸå¤©çš„å­—é«”é¢¨æ ¼ã€‚

                            $sunday=date("w",mktime(0,0,0,$month,$today,$year));

                            $FontStyle = ( $sunday == "0" ) ? "<strong>" : "";

                            return $FontStyle;
                        }

                        $total_quota = 6;
                        $min_quota = 3;

                        $sql = "SELECT * FROM wp_booking_cal_event WHERE cid = " . $_SESSION['cat'] . " AND status = 1";
                        $result = $wpdb->get_results($sql) or die(mysql_error());


                        for($i=0;$i<=$FirstDay;$i++) { //ç”¨forè¼¸å‡ºæ¯å€‹æœˆä¸€è™Ÿçš„ä½ç½®
                            for($i;$i<$FirstDay;$i++) {
                                echo "<td align=left>&nbsp;</td>\n";
                            }

                            if($i==$FirstDay) {
                                echo "<td class='day' align=left ><div class='day_title ".bgcolor($month,$bgtoday,1,$year)."' style=".'color:'.font_color($month,1,$year).">".font_style($month,1,$year);

                                echo "1";
                                $fday = date("Y-m-d",mktime(0,0,0,$month,$i,$year));
                                foreach($result as $r) {

                                    if( $fday == $r -> start_date ) {

                                        $class_starts_at = $r -> start_date . ' ' . $r -> start;
                                        $class_start_date = date( 'd-m-Y', strtotime( $class_starts_at ) );
                                        $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
                                        $class_end_time = date( 'h:i a', strtotime( $r -> end ) );

                                        //starts to organize class information and status to checkout for availability
                                        $class_name = ( $l == 'en' ) ? $r -> title_en : $r -> title;
                                        $class_desc = ( $l == 'en' ) ? $r -> desc_en : $r -> desc;

                                        $class_name = stripcslashes( $class_name );
                                        $class_desc = stripcslashes( $class_desc );

                                        $event_html = '<p class="bg-primary">' . $class_name . '</p>';
                                        $event_html .= '<dl>';
                                        $event_html .= '<dt>' . $class_desc . '</dt>';
                                        $event_html .= '<dd>' . $class_start_time . ' - ' . $class_end_time . '</dd>';
                                        $event_html .= '</dl>';

                                        $event_html .= '<ul class="list-inline" id="status-' . $i . '">';

                                        $check_if_joined = $wpdb -> get_var( "SELECT COUNT(*) FROM wp_booking_join WHERE cid = " . $cat . " AND eid = " . $r -> id . " AND uid = " . $uid . " AND status = 1" );
                                        if ( $check_if_joined > 0 ) {
                                            $event_html .= '<li><span class="label label-warning">' . __('You Are Booked') . '</span></li>';
                                        } else {

                                            $total_joined = $wpdb -> get_var( "SELECT COUNT(*) FROM wp_booking_join WHERE cid = " . $cat . " AND eid = " . $r -> id );
                                            if ( $total_joined < 6 ) {

                                                //check if the class is old or the current time is within the 7 days prior to class start time
                                                $class_time = $class_start_date . ' ' . $class_start_time;
                                                $class_time_seven_days_before = date( 'Y-m-d H:i:s', strtotime( "-7 days", strtotime( $class_time ) ) );

                                                $today = date( 'Y-m-d H:i:s' );

                                                $available_to_book = true;

                                                if ( strtotime( $today ) > strtotime( $class_time ) )
                                                    $available_to_book = false;

                                                if ( ( strtotime( $class_time_seven_days_before ) <= strtotime( $today ) ) && ( strtotime( $class_time ) >= strtotime( $today ) ) )
                                                    $available_to_book = false;

                                                if ( !$available_to_book ) {

                                                    $event_html .= '<li><span class="label label-danger">' . __('Not available to book') . '</span></li>';
                                                } else {

                                                    $event_html .= '<li><span class="label label-info">' . __('Available To Book') . '</span></li>';
                                                    $event_html .= '<li><a href="javascript:void(0);" class="btn btn-success btn-xs book-now" id="' . $i . '|' . $uid . '|' . $cat . '|' . $r -> id . '|' . $lang . '">' . __('Book Now') . '</a></li>';
                                                }
                                            } else {
                                                $event_html .= '<li><span class="label label-danger">' . __('Class Is Full') . '</span></li>';
                                            }

                                        } //end else

                                        $event_html .= '</ul>';

                                        //$a .= '<a href="event/?id='.$r->id.'&'.$u.'='.$uid.'" class="btn btn-success btn-xs event_link" >'.$r->title.'</a><br /><div>5ç©ºä½</div><br />';
                                        $a .= $event_html;
                                    }
                                }
                                echo "</div>

                                <div class='cal_content'>$a</div>

                                </td>\n";
                                $a = '';
                                if( $FirstDay == 6 ) {//åˆ¤æ–·1è™Ÿæ˜¯å¦æ˜ŸæœŸå…­
                                    echo "</tr>";
                                }
                                $html_event ='';
                            }

                        }

                        $countMonth=date("t",mktime(0,0,0,$month,1,$year));//æŸæœˆçš„ç¸½å¤©æ•¸

                        for($i=2;$i<=$countMonth;$i++) {//è¼¸å‡ºç”±1è™Ÿå®šä½,éš¨å¾Œ2è™Ÿç›´è‡³æœˆå°¾çš„æ‰€æœ‰è™Ÿæ•¸

                                echo "<td class='day' align=left ><div class='day_title ".bgcolor($month,$bgtoday,$i,$year)."' style=".'color:'.font_color($month,$i,$year).">".font_style($month,$i,$year);
                                echo $i;

                                $fday = date("Y-m-d",mktime(0,0,0,$month,$i,$year));
                                foreach($result as $r) {

                                    if( $fday == $r -> start_date ) {

                                        $class_name = ( $l == 'en' ) ? $r -> title_en : $r -> title;
                                        $class_desc = ( $l == 'en' ) ? $r -> desc_en : $r -> desc;

                                        $class_name = stripcslashes( $class_name );
                                        $class_desc = stripcslashes( $class_desc );

                                        $class_starts_at = $r -> start_date . ' ' . $r -> start;
                                        $class_start_date = date( 'd-m-Y', strtotime( $class_starts_at ) );
                                        $class_start_time = date( 'h:i a', strtotime( $class_starts_at ) );
                                        $class_end_time = date( 'h:i a', strtotime( $r -> end ) );

                                        $event_html = '<p class="bg-primary">' . $class_name . '</p>';
                                        $event_html .= '<dl>';
                                        $event_html .= '<dt>' . $class_desc . '</dt>';
                                        $event_html .= '<dd>' . $class_start_time . ' - ' . $class_end_time . '</dd>';
                                        $event_html .= '</dl>';

                                        $event_html .= '<ul class="list-inline" id="status-' . $i . '">';

                                        $check_if_joined = $wpdb -> get_var( "SELECT COUNT(*) FROM wp_booking_join WHERE cid = " . $cat . " AND eid = " . $r -> id . " AND uid = " . $uid . " AND status = 1" );
                                        if ( $check_if_joined > 0 ) {
                                            $event_html .= '<li><span class="label label-warning">' . __('You Are Booked') . '</span></li>';
                                        } else {

                                            $total_joined = $wpdb -> get_var( "SELECT COUNT(*) FROM wp_booking_join WHERE cid = " . $cat . " AND eid = " . $r -> id );
                                            if ( $total_joined < 6 ) {

                                                //check if the class is old or the current time is within the 7 days prior to class start time
                                                $class_time = $class_start_date . ' ' . $class_start_time;
                                                $class_time_seven_days_before = date( 'Y-m-d H:i:s', strtotime( "-7 days", strtotime( $class_time ) ) );

                                                $today = date( 'Y-m-d H:i:s' );

                                                $available_to_book = true;

                                                if ( strtotime( $today ) > strtotime( $class_time ) )
                                                    $available_to_book = false;

                                                if ( ( strtotime( $class_time_seven_days_before ) <= strtotime( $today ) ) && ( strtotime( $class_time ) >= strtotime( $today ) ) )
                                                    $available_to_book = false;

                                                if ( !$available_to_book ) {

                                                    $event_html .= '<li><span class="label label-danger">' . __('Not available to book') . '</span></li>';
                                                } else {

                                                    $event_html .= '<li><span class="label label-info">' . __('Available To Book') . '</span></li>';
                                                    $event_html .= '<li><a href="javascript:void(0);" class="btn btn-success btn-xs book-now" id="' . $i . '|' . $uid . '|' . $cat . '|' . $r -> id . '|' . $lang . '">' . __('Book Now') . '</a></li>';
                                                }
                                            } else {
                                                $event_html .= '<li><span class="label label-danger">' . __('Class Is Full') . '</span></li>';
                                            }

                                        } //end else

                                        $event_html .= '</ul>';

                                        //$b .= '<a href="event/?id='.$r->id.'&'.$u.'='.$uid.'" class="btn btn-success btn-xs event_link" >'.$r->title.'</a><br /><div>5ç©ºä½</div><br />';
                                        $b .= $event_html;
                                    }
                                }

                                echo "</div>

                                <div class='cal_content'>$b</div>

                                </td>\n";
                                $b = '';

                                if(date("w",mktime(0,0,0,$month,$i,$year))==6) {//åˆ¤æ–·è©²æ—¥æ˜¯å¦æ˜ŸæœŸå…­
                                    echo "</tr>\n";
                                }
                                $b ='';

                        }

                        ?>

                        </table>

                        <div class="modal fade" id="conf_modal" tabindex="-1" role="dialog" aria-labelledby="conf_modalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="conf_modalLabel"><?php echo __( 'You are about to book a class' ) ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <p><?php echo __( 'Are you sure you want to do this?' ) ?></p>
                                        <form method="post" action="" id="frm_book">
                                            <input type="hidden" name="class_data" id="class_data" value="">
                                        </form>
                                        <p id="preloader" style="display:none;"><?php echo __( 'please wait while we process your booking...' ) ?></p>
                                        <p class="bg-danger" id="booking_error" style="display:none;"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" id="btn-cancel" data-dismiss="modal"><?php echo __( 'Cancel' ) ?></button>
                                        <button type="button" class="btn btn-primary" id="btn_confirm_booking"><?php echo __( 'OK' ) ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>

                    </div>


                </div><!-- end content -->
            </section><!-- end primary -->
            <?php } ?>

            <div class="panel panel-primary" id="booked_class_details" style="display: none;">
                <div class="panel-heading" id="booked_class_title"></div>
                <div class="panel-body">
                    <dl>
                        <dt id="booked_class_desc"></dt>
                        <dd id="booked_class_time"></dd>
                    </dl>
                </div>
            </div>

        </div>
    </div><!-- end main -->

<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('.book-now').on('click', function(e){

            var btn_id = $(this).attr('id');
            $( "#class_data" ).val( btn_id );

            $('#conf_modal').modal({ backdrop: 'static', keyboard: false })
                .one('click', '#btn_confirm_booking', function (e) {

		    $( "div.alert" ).hide();

                    $( "#btn_confirm_booking" ).hide();
                    $( "#btn_cancel" ).hide();
                    $( "#preloader" ).show();

                    // handle booking
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo $ajax_url ?>",
                        data: {
                            'action':'join_an_event',
                            'event_join_data' : $( "#class_data" ).val()
                        },
                        dataType: 'json',
                        success:function(data) {

                            if ( data.status == 'success' ) {

                                $( "#calendar-section" ).hide();

                                var class_data = data.class_data;
                                $( "#booked_class_title" ).html( class_data.title );
                                $( "#booked_class_desc" ).html( class_data.desc );
                                $( "#booked_class_time" ).html( class_data.time );
                                $( "#booked_class_details" ).show();

                                $('#conf_modal').modal('hide');
                            } else {

                                $('#class_data').val( '' );
                                $( "#btn_confirm_booking" ).show();
                                $( "#btn_cancel" ).show();
                                $( "#preloader" ).hide();
                                $( "#booking_error" ).html( data.message );
                            }



                        },
                        error: function(errorThrown){
                            console.log(errorThrown);
                        }
                    });

                });
        });

    });
</script>

<?php get_sidebar('footer') ?>
<?php get_footer() ?>