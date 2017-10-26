<?php
/* Template Name: BOOKING CLASS LOGIN */

session_start();

get_header();
$l = ICL_LANGUAGE_CODE;

if ( $l == 'en' ) {
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-login-process';
    
} else { 
    $login_url = 'http://' . $_SERVER['SERVER_NAME'] . '/cooking-class-login-process/?lang=zh-hant';
}
?>

<link rel="stylesheet" href='<?php echo get_template_directory_uri()?>/css/fonts/font-awesome/font-awesome.min.css'>

<div id="main" class="list-post <?php axiom_the_page_sidebar_pos($post->ID)?>">

    <div class="wrapper fold clearfix">

        <div class="row">
            
            <?php if( isset( $_SESSION[ 'error_message' ] ) ): ?>
                <div class="alert alert-danger">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $_SESSION[ 'error_message' ] ?>
                </div>
                <?php unset( $_SESSION[ 'error_message' ] ) ?>
            <?php endif ?>
            
            <div class="col-xs-6">
                
                <div class="well">
                    <form id="cooking-class-login" method="POST" action="<?php echo $login_url ?>">
                        
                        <div class="form-group" id="form-group-login-id">
                            <div class="col-sm-12">
                                <label for="login-id" class="control-label"><?php echo __('Login ID')?></label>
                                <input type="text" id="login-id" name="login-id">
                            </div>                            
                        </div>

                        <div class="form-group" id="form-group-login-pass">
                            <div class="col-sm-12">
                                <label for="login-pass" class="control-label"><?php echo __('Password')?></label>
                                <input type="password" id="login-pass" name="login-pass">
                            </div>                            
                        </div>
                        
                        <div class="form-group">
                            <button type="button" id="btn-login" class="btn btn-success btn-block"><span class="fa fa-lock"></span> <?php echo __( 'Login' ) ?></button>
                        </div>
                        <div class="form-group">
                            <button type="reset" id="btn-reset" class="btn btn-default btn-block"><span class="fa fa-chain-broken"></span> <?php echo __( 'Reset' ) ?></button>
                        </div>
                        
                    </form>
                </div>
                
            </div>
            
            <div class="col-xs-6">
                <p class="lead"><?php echo __('Login now to')?> <span class="text-success"></span></p>
                <ul class="list-unstyled" style="line-height: 2">
                    <li><span class="fa fa-unlock text-success"></span> <?php echo __('Please enter Login ID & Password.')?></li>
                    <li><span class="fa fa-unlock text-success"></span> <?php echo __('You may check it from your email account if you have already submitted the application.')?></li>
                    <li><span class="fa fa-hand-o-right text-danger"></span> <?php echo __('Your account will be blocked if you do not book your class 3 months from getting our login email.')?></li>
                </ul>
            </div>
            
        </div>

    </div>

</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        $("#btn-login").on("click", function () {
            
            var login_id = $( "#login-id" ).val();
            var login_pass = $( "#login-pass" ).val();
            
            if ( ( login_id != '' ) && ( login_pass != '' ) ) {
                
                $( "#cooking-class-login" ).submit();
                
            } else {
                
                if ( login_id == '' ) {
                    
                    $( "#form-group-login-id" ).attr( 'class', 'form-group has-error has-feedback' );
                    $( "#login-id" ).attr( 'style', 'background-color:yellow;' );
                }//end if
                
                if ( login_pass == '' ) {
                    
                    $( "#form-group-login-pass" ).attr( 'class', 'form-group has-error has-feedback' );
                    $( "#login-pass" ).attr( 'style', 'background-color:yellow' );
                }//end if
                
            }//end else
        });

    });
</script>

<?php get_sidebar('footer') ?>
<?php get_footer() ?>