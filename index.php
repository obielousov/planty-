<?php

/**
 *
 * @package planty
 */

get_header();
?>

<main class="page" id="primary">
	<?php get_template_part('template-parts/section', 'hero'); ?>
	<?php get_template_part('template-parts/section', 'deal'); ?>
	<?php get_template_part('template-parts/section', 'living'); ?>
	<?php get_template_part('template-parts/section', 'shop'); ?>
	<?php get_template_part('template-parts/section', 'find'); ?>
	<?php get_template_part('template-parts/section', 'plant'); ?>
	<?php get_template_part('template-parts/section', 'collection'); ?>
	<?php get_template_part('template-parts/section', 'reviews'); ?>
</main>

<?php
get_footer();
