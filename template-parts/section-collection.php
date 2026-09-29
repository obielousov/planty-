<?php
/**
 * Collection section.
 *
 * @package planty
 */

$planty_collection_title = get_theme_mod('planty_collection_title', 'Plant Collection');

$planty_card_1_title = get_theme_mod('planty_collection_1_title', 'Indoor Plants');
$planty_card_1_link_text = get_theme_mod('planty_collection_1_link_text', 'Shop Now');
$planty_card_1_link = get_theme_mod('planty_collection_1_link', '#');
$planty_card_1_image = get_theme_mod('planty_collection_1_image');

if (empty($planty_card_1_image)) {
	$planty_card_1_image = planty_asset('img/collection/collection01.jpg');
}

$planty_card_2_title = get_theme_mod('planty_collection_2_title', 'Outdoor Plants');
$planty_card_2_link_text = get_theme_mod('planty_collection_2_link_text', 'Shop Now');
$planty_card_2_link = get_theme_mod('planty_collection_2_link', '#');
$planty_card_2_image = get_theme_mod('planty_collection_2_image');

if (empty($planty_card_2_image)) {
	$planty_card_2_image = planty_asset('img/collection/collection02.jpg');
}
?>
<section class="collection">
	<div class="collection__container">
		<h2 class="section__title  collection__title"><?php echo esc_html($planty_collection_title); ?></h2>
		<div class="collection__content content-collection">
			<div class="content-collection__item item-collection">
				<div class="item-collection__content">
					<h3 class="item-collection__title"><?php echo esc_html($planty_card_1_title); ?></h3>
					<a href="<?php echo esc_url($planty_card_1_link); ?>" class="item-collection__link"
						aria-label="<?php echo esc_attr($planty_card_1_link_text . ' — ' . $planty_card_1_title); ?>">
						<?php echo esc_html($planty_card_1_link_text); ?>
					</a>
				</div>
				<div class="item-collection__bg">
					<img class="item-collection__image" src="<?php echo esc_url($planty_card_1_image); ?>"
						alt="" />
				</div>
			</div>
			<div class="content-collection__item item-collection">
				<div class="item-collection__content">
					<h3 class="item-collection__title"><?php echo esc_html($planty_card_2_title); ?></h3>
					<a href="<?php echo esc_url($planty_card_2_link); ?>" class="item-collection__link"
						aria-label="<?php echo esc_attr($planty_card_2_link_text . ' — ' . $planty_card_2_title); ?>">
						<?php echo esc_html($planty_card_2_link_text); ?>
					</a>
				</div>
				<div class="item-collection__bg">
					<img class="item-collection__image" src="<?php echo esc_url($planty_card_2_image); ?>"
						alt="" />
				</div>
			</div>
		</div>
	</div>
</section>
