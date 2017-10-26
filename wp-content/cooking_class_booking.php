<?php
/* Template Name: COOKING CLASS BOOKING */

get_header();
$l = ICL_LANGUAGE_CODE;

if ( $l == 'en' ) {
    $registration_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-form';
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-booking-login';
    
} else { 
    $registration_url = 'http://' . $_SERVER['SERVER_NAME'] . '/烹飪課程-表格/?lang=zh-hant';
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-booking-login/?lang=zh-hant';
	
}
?>

<div id="main" class="list-post <?php axiom_the_page_sidebar_pos($post->ID)?>">
    <div class="wrapper fold clearfix">
        
        <div class="row">
            
            <div class="alert alert-info">
                <p>
                    <?php echo __( 'All classes need to be booked online.' ) ?><br />
                    <?php echo __( 'Please login below to book your class.' ) ?>
                </p>
            </div>
            
            <a href="<?php echo $login_url ?>" class="btn btn-success btn-lg btn-block" role="button">
                <span class="glyphicon glyphicon-lock"></span>&nbsp;&nbsp;<?php echo __( 'Class Booking' ) ?>
            </a>
        </div>
        <div class="row">
            
            <div class="alert alert-info">
                <p>
                    <?php echo __( 'If you do not have the login ID & Password, please click registration below to get one.' ) ?><br />
                    <?php echo __( 'Should you have any enquiries, please contact at 3412 0183.' ) ?><br />
                    <?php echo __( '(Monday – Friday 9:00am – 12:30pm, 1:30pm – 6:00pm, closed on Saturday, Sunday and Public Holidays)' ) ?>
                </p>
            </div>
            
           <a href="<?php echo $registration_url ?>" class="btn btn-primary btn-lg btn-block" role="button">
                <span class="glyphicon glyphicon-paperclip"></span>&nbsp;&nbsp;<?php echo __( 'Login Registration' ) ?><br/>
                <!--<small><?php echo __( '(Product purchase detail and invoice image required)' ) ?></small>-->
           </a>
        </div>
    </div>
</div>
<?php get_sidebar('footer')?>
<?php
get_footer()?>