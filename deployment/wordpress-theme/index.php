<?php
/**
 * Main template file.
 */
get_header();
?>
<div class="cai-container" style="padding: 100px 20px; max-width: 1200px; margin: 0 auto; color: #fff;">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_title('<h1>', '</h1>');
            the_content();
        endwhile;
    endif;
    ?>
</div>
<?php
get_footer();
