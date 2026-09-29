<?php
/**
 * Find section.
 *
 * @package planty
 */

$planty_find_title = get_theme_mod('planty_find_title', 'Find A Perfect Plant For Your Home');
$planty_find_link_text = get_theme_mod('planty_find_link_text', 'Get Started');
$planty_find_link = get_theme_mod('planty_find_link', '#');
$planty_find_image = get_theme_mod('planty_find_image');

if (empty($planty_find_image)) {
	$planty_find_image = planty_asset('img/bg/bg-plant.jpg');
}
?>
<section class="find">
	<div class="find__content">
		<h2 class="find__title"><?php echo esc_html($planty_find_title); ?></h2>
		<a href="<?php echo esc_url($planty_find_link); ?>"
			class="find__link"><?php echo esc_html($planty_find_link_text); ?></a>
	</div>
	<div class="find__bg">
		<img class="find__image" src="<?php echo esc_url($planty_find_image); ?>" alt="" />
	</div>
</section>
