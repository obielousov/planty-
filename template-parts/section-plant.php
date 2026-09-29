<?php

/**
 * Plant section. The button is shared by every slide and comes from the
 * planty_plant Customizer section, the slides themselves come from the
 * planty_plant_slide post type.
 */

$planty_plant_link_text = get_theme_mod('planty_plant_link_text');

// get_theme_mod falls back only when nothing is stored at all, so a value
// saved as an empty string in the Customizer has to be caught here as well
if (empty($planty_plant_link_text)) {
	$planty_plant_link_text = 'Get More Information';
}

$planty_plant_link = get_theme_mod('planty_plant_link');

if (empty($planty_plant_link)) {
	$planty_plant_link = '#';
}

// Slides are ordered by the "Order" field in the editor, so the client decides
// which one the slider opens with
$planty_plant_slides = new WP_Query(
	array(
		'post_type' => 'planty_plant_slide',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'no_found_rows' => true,
	)
);
?>
<section class="plant">
	<?php if ($planty_plant_slides->have_posts()) : ?>
		<div class="plant__slider swiper" data-reveal>
			<div class="plant__wrapper swiper-wrapper">
				<?php while ($planty_plant_slides->have_posts()) : ?>
					<?php
					$planty_plant_slides->the_post();

					$planty_image = get_the_post_thumbnail_url(null, 'large');

					if (empty($planty_image)) {
						$planty_image = planty_asset('img/plant/plant.png');
					}
					?>
					<div class="plant__slide swiper-slide">
						<div class="plant__container">
							<div class="plant__media">
								<img src="<?php echo esc_url($planty_image); ?>" alt=""
									class="plant__image" />
							</div>
							<div class="plant__body body-plant">
								<h3 class="body-plant__title"><?php the_title(); ?></h3>
								<div class="body-plant__text">
									<?php the_content(); ?>
								</div>
								<a href="<?php echo esc_url($planty_plant_link); ?>"
									class="body-plant__link"><?php echo esc_html($planty_plant_link_text); ?></a>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
			<div class="plant__pagination"></div>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</section>
