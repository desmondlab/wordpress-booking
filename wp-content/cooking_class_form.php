<?php
/* Template Name: BOOKING CLASS FORM */

get_header();
$l = ICL_LANGUAGE_CODE; // 'en', 'zh-hant'

$ajax_url = 'http://' . $_SERVER['SERVER_NAME'] . '/wp-admin/admin-ajax.php';

if ( $l == 'en' ) {
    $post_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-booking-registration';
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] .  '/cooking-class-booking-login';
    $terms_url = 'http://' . $_SERVER['SERVER_NAME'] . '/terms-and-conditions';
    
} else { 
    $post_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-booking-registration/?lang=zh-hant';
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-booking-login/?lang=zh-hant';
    $terms_url = 'http://' . $_SERVER['SERVER_NAME'] . '/terms-and-conditions/?lang=zh-hant';
}

?>

<link rel="stylesheet" href='<?php echo get_template_directory_uri()?>/css/fonts/font-awesome/font-awesome.min.css'>

<div id="main" class="list-post <?php axiom_the_page_sidebar_pos($post->ID)?>">

    <div class="wrapper fold clearfix">

        <div class="row">
            
            <div class="col-xs-6">
                
                <div class="well">
                    <form id="cooking-class-form" method="POST" action="<?php echo $post_url ?>" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label for="fname" class="control-label"><?php echo __('First Name')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                <input name="fname" id="fname" required type="text">
                            </div>                            
                            <div class="col-sm-6">
                                <label for="lname" class="control-label"><?php echo __('Last Name')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                <input name="lname" id="lname" required type="text">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="phone" class="control-label"><?php echo __('Contact Number')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>                            
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="email" class="control-label"><?php echo __('Email Address')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>

                        <?php $result = $wpdb->get_results("SELECT * FROM wp_booking_brand WHERE status=1") or die(mysql_error())?>
                        <?php if (count($result) > 0):?>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label for="bid" class="control-label"><?php echo __('Purchased Product Brand')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                    <select class="form-control" id="bid" name="bid" required>
                                        <option value=""><?php echo __('Please Select')?></option>
                                        <?php foreach ($result as $rb):?>
                                            <?php $title = ( $l == 'en' ) ? $rb->title_en : $rb->title?>
                                            <option value="<?php echo $rb->id?>"><?php echo $title?></option>
                                        <?php endforeach?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <div id='model_selector'>
                                        <label for="model" class="control-label"><?php echo __('Purchased Model')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                        <select class="form-control" id="model" name="model" required disabled="disabled"></select>
                                    </div>
                                </div>
                            </div>
                        <?php endif?>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="retail_outlet" class="control-label"><?php echo __('Retail Outlet')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                <select class="form-control" id="retail-outlet" name="retail_outlet" required>
                                    <option value=""><?php echo __('Please Select')?></option>
                                    <option value="<?php echo __('Sogo')?>"><?php echo __('Sogo')?></option>
                                    <option value="<?php echo __('Fortress')?>"><?php echo __('Fortress')?></option>
                                    <option value="<?php echo __('Broadway')?>"><?php echo __('Broadway')?></option>
                                    <option value="<?php echo __('Suning')?>"><?php echo __('Suning')?></option>
                                    <option value="<?php echo __('Jusco')?>"><?php echo __('Jusco')?></option>
                                    <option value="<?php echo __('Wing On')?>"><?php echo __('Wing On')?></option>
                                    <option value="<?php echo __('Yata')?>"><?php echo __('Yata')?></option>
                                    <option value="<?php echo __('Citistore')?>"><?php echo __('Citistore')?></option>
                                    <option value="<?php echo __('Others')?>"><?php echo __('Others')?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="delivery_date" class="control-label"><?php echo __('Delivery Date(if applicable)')?></label>
                                <input type="text" id="delivery-date" name="delivery_date" data-provide="datepicker">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="invoice-img"><?php echo __('Please Upload Receipt')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                <input type="file" accept="file_extension|image/*"  name="invoice_img" required id="invoice-img">
                                <span class="help-block"><?php echo __( 'images only: .gif, .jpg, .png' ) ?></span>
                                <span class="help-block"><?php echo __( 'Registration will be forfeited after one month of purchase.' ) ?></span>
                                <br />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="invitation-letter-img"><?php echo __('Please Upload Class Invitation Letter')?> <strong style="font-weight:bold; color: red;">*</strong></label>
                                <input type="file" accept="file_extension|image/*"  name="invitation_letter_img" required id="invitation-letter-img">
                                <span class="help-block"><?php echo __( 'images only: .gif, .jpg, .png' ) ?></span>
                                <br />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="cooking_class_in_english" value="1">
                                        <?php echo __( 'Class in English only')?>
                                    </label>
                                </div><br />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                      <input type="checkbox" id="agree_terms" value="1" required>
                                      <?php echo __( 'I read and agree to the ')?> <a href="<?php echo $terms_url ?>" target="_blank"><?php echo __('Terms and Conditions')?></a>
                                       <strong style="font-weight:bold; color: red;">*</strong>
                                    </label>
                                </div><br />
                            </div>
                        </div>

                        <div class="col-sm-12">
                          <center>
                              <label><strong style="font-weight:bold; color: red;">* </strong><strong><?php echo __( 'Compulsory' ) ?></strong></label>
                              <br>
                          </center>
                        </div>
                        
                        <div class="form-group">
                            <input type="hidden" name="lang" value="<?php echo $l ?>">
                            <input type="hidden" name="cid" value="0">
                            <input type="hidden" name="sex" value="0">
                            <input type="hidden" name="buy_date" value="">
                            <input type="hidden" name="invoice" value="">
                            <input type="hidden" name="tc" value="">
                            <input type="hidden" name="status" value="0">
                            <input type="hidden" name="join" value="0">
                            <input type="hidden" name="deadline" value="">
                            
                            <input type="submit" class="btn btn-lg btn-success btn-block" id="btn-submit" value="<?php echo __( 'Submit' ) ?>" style="height:50px; font-size:18px;">
                        </div>
                        <div class="form-group">
                            <input type="reset" class="btn btn-lg btn-default btn-block" id="btn-submit" value="<?php echo __( 'Reset' ) ?>" style="height:50px; font-size:18px;">
                        </div>
                        
                    </form>
                </div>
                
            </div>
            
            <div class="col-xs-6">
                <p class="lead"><!--<?php echo __('Register now to book your class for')?> <span class="text-success"><?php echo __('FREE')?></span></p>-->
                <span class="text-success"><?php echo __('Register now to book your class for FREE')?></span></p>
                <ul class="list-unstyled" style="line-height: 2">
                    <li><span class="fa fa-hand-o-right text-success"></span> <?php echo __('Please fill in the form to register.')?></li>
                    <li><span class="fa fa-hand-o-right text-success"></span> <?php echo __('After submitting the form, our administrator will verify your eligibility. This usually takes 5 working days.')?></li>
                    <li><span class="fa fa-hand-o-right text-success"></span> <?php echo __('Once verified, you will receive an email with login ID and password for class registration.')?></li>
                    <li><span class="fa fa-hand-o-right text-success"></span> <?php echo __('Should you have any enquiries, please contact at 3412 0183.')?></li>
                    <li><span class="fa fa-hand-o-right text-success"></span> <?php echo __('(Monday – Friday 9:00am – 12:30pm, 1:30pm – 6:00pm, closed on Saturday, Sunday and Public Holidays)')?></li>
                </ul>
                <p>
                    <a href="<?php echo $login_url ?>" class="btn btn-info btn-block">
                        <span class="fa fa-lock"></span> <?php echo __( 'Registered already? Login now!' ) ?>
                    </a>
                </p>
            </div>
            
        </div>

    </div>

</div>
<script>
    
    $(function () {
        
        $('select[name="bid"]').on('change', function () {
            
            $("#model").attr("disabled", "disabled");
            
            $.ajax({
                type: 'POST',
                url: "<?php echo $ajax_url ?>",
                data: {
                    'action':'get_models_by_brand',
                    'brand_id' : $(this).val()
                },
                dataType: 'json',
                success:function(data) {
                    
                    var options = '';
                    
                    $.each(data, function(model_title, model_id) {

                        options+= '<option value="' + model_id + '">' + model_title + '</option>';
                    });
                    
                    $("#model").removeAttr("disabled");
                    $("#model").html(options);
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
            
        });

    });

</script>

<?php get_sidebar('footer') ?>
<?php get_footer() ?>