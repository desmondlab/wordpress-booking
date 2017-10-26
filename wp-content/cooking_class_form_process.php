<?php
/* Template Name: REGISTRATION PROCESS */

/** cooking-class-booking-registration **/
get_header();
$l = ICL_LANGUAGE_CODE;
?>

<div id="main" class="list-post <?php axiom_the_page_sidebar_pos($post->ID) ?>">
    <div class="wrapper fold clearfix">
        <div class="row">
            <?php
                
                if ( isset( $_POST[ 'email' ] ) ) {
                    
                    $query_email = $wpdb->get_results("SELECT email FROM wp_booking_user WHERE email = '" . $_POST[ 'email' ] . "' LIMIT 1");

                    if ( count( $query_email ) > 0) { // duplicate email found
                        
                        echo '<div class="alert alert-danger">';
                        echo '<p>' . __('<strong>ERROR</strong>: This email is already registered, please choose another one.') . '<br />';
                        echo __( 'To get back to the registration page ' );
                        echo '<a href="' . home_url( '/' ) . 'cooking-class-form" type="button" class="btn btn-link">' . __( 'click here' ) . '</a>';
                        echo '</p></div>';

                        exit;
                    }// end if

                    $wpdb -> flush();

                    /**
                     * Get an unused serial for user registration
                     *
                     * @return  int between 1200 and 4294967295
                     */
                    function get_unused_serial()
                    {
                        // Create a random user id between 1200 and 4294967295
                        $random_unique_int = 2147483648 + mt_rand(-2147482448, 2147483647);
                        
                        global $wpdb;

                        // Make sure the random user_id isn't already in use
                        $query = $wpdb -> get_results("SELECT serial FROM wp_booking_user WHERE serial = " . $random_unique_int . " LIMIT 1");

                        if ( count( $query ) > 0) {
                            $wpdb->flush();

                            // If the random user_id is already in use, try again
                            return $this -> get_unused_serial();
                        }

                        return $random_unique_int;
                    }

                    $serial = get_unused_serial();
                    $_POST[ 'serial' ] = $serial;
                    
                    //starts to upload the images
                    $invoice_uploaddir = 'invoice/';
                    $invitation_letter_uploaddir = 'invitation_letter/';
                    
                    $invoice_image = Date('Ymdhis') . '_' . basename( $_FILES[ 'invoice_img' ][ 'name' ] );
                    $invitation_letter_image = Date('Ymdhis') . '_' . basename( $_FILES[ 'invitation_letter_img' ][ 'name' ] );
                    
                    $invoice_uploadfile = ABSPATH . $invoice_uploaddir . Date('Ymdhis') . '_' . basename($_FILES['invoice_img']['name']);
                    $invitation_letter_uploadfile = ABSPATH . $invitation_letter_uploaddir . Date('Ymdhis') . '_' . basename($_FILES['invitation_letter_img']['name']);
                    
                    $num_of_uploads = 0;
                    
                    if (move_uploaded_file( $_FILES['invoice_img']['tmp_name'], $invoice_uploadfile ) ) {
                        $_POST['invoice_img'] = Date('Ymdhis') . '_' . basename($_FILES['invoice_img']['name']);
                        $num_of_uploads += 1;
                    }
                    
                    if (move_uploaded_file( $_FILES['invitation_letter_img']['tmp_name'], $invitation_letter_uploadfile ) ) {
                        $_POST['invitation_letter_img'] = Date('Ymdhis') . '_' . basename($_FILES['invitation_letter_img']['name']);
                        $num_of_uploads += 1;
                    }
                    
                    //checks whether all files are uploaded; save user data upon success
                    if ( $num_of_uploads == 2 ) {
                        
                        $user_pass = substr( $serial, 0, 4 );
                        
                        $_POST['user_pass'] = sha1( $user_pass );
                        $_POST['create_time'] = Date('Y-m-d H:i:s');

                        $save_data = $wpdb->insert('wp_booking_user', $_POST);
                        
                        if ( $save_data ) {
                            echo '<div class="alert alert-success">';
                            echo __('Registration Completed. Thank you for your information.');
                            echo '</div>';
                            
                            //send a thank you email
                            $email_message = '<p>' . __( 'Dear valued customer,' ) . '<br><br>';
                            $email_message .= __( 'Thank you for your registration.' ) . '<br>';
                            $email_message .= __( 'Our administrator will review your information. If everything is valid, a set of login and password will be sent to you via email.' ) . '<br>';
                            $email_message .= __( 'The review process will take around 5 working days.' ) . '<br><br>';
                            $email_message .= __( 'Best Regards,' ) . '<br>';
                            $email_message .= __( 'Janet Cheung' ) . '<br>';
                            $email_message .= '<img src="http://delonghiacademy.com.hk/wp-content/themes/lotus/images/email_signature.png"/><br>';
                            $email_message .= "De'Longhi Kenwood A.P.A. Ltd.<br>";
                            $email_message .= __( '16/F., Tins Enterprises Centre, 777 Lai Chi Kok Road, Cheung Sha Wan, Kowloon, Hong Kong' ) . '<br>';
                            $email_message .= __( 'Janet.cheung@delonghigroup.com' ) . '<br>';
                            $email_message .= __( 'www.delonghigroup.com' );
                            $email_message .= '</p>';

                            $headers = array('Content-Type: text/html; charset=UTF-8', 'Delonghi HK <info.hk@delonghigroup.com>');
                            $subject = "De'Longhi Group Academy - Class Registration";

                            wp_mail( $_POST['email'], $subject, $email_message, $headers );
                            
                        } else {
                            echo '<div class="alert alert-danger">';
                            echo '<p>' . __('<strong>ERROR</strong>: Information could not be saved; please try again later.') . '<br />';
                            echo __( 'To get back to the registration page ' );
                            echo '<a href="' . home_url( '/' ) . 'cooking-class-form" type="button" class="btn btn-link">' . __( 'click here' ) . '</a>';
                            echo '</p></div>';                            
                        }
                        
                    } else { //either all or some files were not uploaded
                        echo '<div class="alert alert-danger">';
                        echo '<p>' . __('<strong>ERROR</strong>: Either all or some files could not be uploaded, please try again later.') . '<br />';
                        echo __( 'To get back to the registration page ' );
                        echo '<a href="' . home_url( '/' ) . 'cooking-class-form" type="button" class="btn btn-link">' . __( 'click here' ) . '</a>';
                        echo '</p></div>';                        
                    }
                    //ends to upload the images
                    
                }//end main if
            
            ?>
        </div>
    </div>
</div>
<?php get_sidebar('footer') ?>
<?php get_footer() ?>