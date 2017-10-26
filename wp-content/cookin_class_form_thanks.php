<?php
/* Template Name: REGISTRATION SUCCESS */

get_header();
$l = ICL_LANGUAGE_CODE;
?>

<div id="main" class="list-post <?php axiom_the_page_sidebar_pos($post->ID) ?>">
    <div class="wrapper fold clearfix">
        <div class="row">
            <p class="bg-success">
                <?php echo __( 'Thanks for information. We will verify the information and send you login ID & password to you for login.' ) ?>
            </p>
        </div>
    </div>
</div>

<?php get_sidebar('footer') ?>
<?php get_footer() ?>