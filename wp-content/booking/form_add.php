<?php

session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/~delonghi/wp-config.php' );

global $wpdb;

//check if email is already taken
$query_email = $wpdb->get_results("SELECT email FROM wp_booking_user WHERE email = '" . $_POST[ 'email' ] . "' LIMIT 1");

if ( count( $query_email ) > 0) { // duplicate email found
    //sleep for 10 seconds
    sleep(10);
    
    echo '<p class="bg-danger">' . __('<strong>ERROR</strong>: This email is already registered, please choose another one.') . '</p>';
    
    echo '<p class="bg-warning">';
    echo __( 'You will be redirected to the registration page shortly' );
    echo '<br />';
    echo __( 'If you are not redirected within 10 seconds, then: ' );
    echo '<a href="' . home_url( '/' ) . 'cooking-class-form" type="button" class="btn btn-link">' . __( 'click here' ) . '</a>';
    echo '</p>';
    
    exit;
}// end if

$wpdb->flush();

/**
 * Get an unused serial for user registration
 *
 * @return  int between 1200 and 4294967295
 */
function get_unused_serial()
{
    // Create a random user id between 1200 and 4294967295
    $random_unique_int = 2147483648 + mt_rand(-2147482448, 2147483647);

    // Make sure the random user_id isn't already in use
    $query = $wpdb->get_results("SELECT serial FROM wp_booking_user WHERE serial = " . $random_unique_int . " LIMIT 1");

    if ( count( $query ) > 0) {
        $wpdb->flush();

        // If the random user_id is already in use, try again
        return $this->get_unused_serial();
    }

    return $random_unique_int;
}

$_POST[ 'serial' ] = get_unused_serial();

$invoice_uploaddir = '/invoice/';
$invitation_letter_uploaddir = '/invitation_letter/';

$invoice_uploadfile = $_SERVER["DOCUMENT_ROOT"] . '/~delonghi/' . $invoice_uploaddir . Date('Ymdhis').'_'.basename($_FILES['invoice_img']['name']);

$invitation_letter_uploadfile = $_SERVER["DOCUMENT_ROOT"] . '/~delonghi/' . $invitation_letter_uploaddir . Date('Ymdhis').'_'.basename($_FILES['invitation_letter_img']['name']);

if (move_uploaded_file($_FILES['invoice_img']['tmp_name'], $uploadfile)) {
    $_POST['invoice_img'] = Date('Ymdhis') . '_' . basename($_FILES['invoice_img']['name']);
    $_POST['create_time'] = Date('Y-m-d H:i:s');

    $wpdb->insert('wp_booking_user', $_POST);

    header('Location:/verify');
}
?>
<?php get_sidebar('footer') ?>
<?php get_footer() ?>