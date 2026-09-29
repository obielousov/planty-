<?php

/**
 * Living section.
 *
 * @package planty
 */

$planty_living_title = get_theme_mod('planty_living_title', 'LIVING THING GROWS');
$planty_living_text = get_theme_mod('planty_living_text', 'You have no plants & are you ready to grow ? come and shop with us.');
$planty_living_price = get_theme_mod('planty_living_price', 'Price From $ 45.00');
$planty_living_link_text = get_theme_mod('planty_living_link_text', 'Shop Now');
$planty_living_link = get_theme_mod('planty_living_link', '#');
?>
<section class="living">
	<div class="living__container">
		<h2 class="living__title"><?php echo esc_html($planty_living_title); ?></h2>
		<div class="living__text">
			<p><?php echo esc_html($planty_living_text); ?></p>
		</div>
		<div class="living__price">
			<span class="living__label"><?php echo esc_html($planty_living_price); ?></span>
		</div>
		<a href="<?php echo esc_url($planty_living_link); ?>"
			class="living__link"><?php echo esc_html($planty_living_link_text); ?></a>
		<div class="living__decor decor-living">
			<img src="<?php echo esc_url(planty_asset('img/layers/flower-layer02.png')); ?>" alt=""
				class="decor-living__image decor-living__image--l" />
			<img src="<?php echo esc_url(planty_asset('img/layers/flower-layer03.png')); ?>" alt=""
				class="decor-living__image decor-living__image--r" />
		</div>
	</div>
</section>