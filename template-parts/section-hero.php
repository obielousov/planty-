<?php

/**
 * Hero section. The three shared texts come from the planty_hero Customizer
 * section, the slides themselves come from the planty_slide post type.
 */

$planty_hero_subtitle = get_theme_mod('planty_hero_subtitle');

// get_theme_mod falls back only when nothing is stored at all, so a value
// saved as an empty string in the Customizer has to be caught here as well
if (empty($planty_hero_subtitle)) {
	$planty_hero_subtitle = 'Let’s Bring';
}

$planty_hero_link_text = get_theme_mod('planty_hero_link_text');

if (empty($planty_hero_link_text)) {
	$planty_hero_link_text = 'Shop Now';
}

$planty_hero_link = get_theme_mod('planty_hero_link');

if (empty($planty_hero_link)) {
	$planty_hero_link = '#';
}

// Slides are ordered by the "Order" field in the editor, so the client decides
// which one the slider opens with
$planty_slides = new WP_Query(
	array(
		'post_type' => 'planty_slide',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'no_found_rows' => true,
	)
);
?>
<section class="hero">
	<img src="<?php echo esc_url(planty_asset('img/plant/hero-plant01.png')); ?>" alt=""
		class="hero__decor hero__decor--top" />
	<img src="<?php echo esc_url(planty_asset('img/layers/hero-layer02.png')); ?>" alt=""
		class="hero__decor hero__decor--bottom" />
	<img src="<?php echo esc_url(planty_asset('img/plant/hero-plant03.png')); ?>" alt=""
		class="hero__decor hero__decor--top-right" />
	<?php if ($planty_slides->have_posts()) : ?>
		<div class="hero__slider swiper">
			<div class="hero__wrapper swiper-wrapper">
				<?php
				$planty_index = 0;

				while ($planty_slides->have_posts()) :
					$planty_slides->the_post();

					$planty_index++;

					// The first slide carries the page h1, the rest are h2
					$planty_tag = (1 === $planty_index) ? 'h1' : 'h2';

					$planty_price = get_post_meta(
						get_the_ID(),
						'planty_slide_price',
						true
					);

					$planty_image = get_the_post_thumbnail_url(null, 'large');

					if (empty($planty_image)) {
						$planty_image = planty_asset('img/plant/plant.png');
					}
					?>
					<div class="hero__slide swiper-slide">
						<div class="hero__container">
							<div class="hero__content">
								<<?php echo tag_escape($planty_tag); ?> class="hero__title">
									<span class="hero__subtitle">
										<?php echo esc_html($planty_hero_subtitle); ?>
									</span>
									<?php the_title(); ?>
								</<?php echo tag_escape($planty_tag); ?>>
								<div class="hero__text">
									<?php the_content(); ?>
								</div>
								<div class="hero__cta">
									<a href="<?php echo esc_url($planty_hero_link); ?>" class="hero__link">
										<?php echo esc_html($planty_hero_link_text); ?>
									</a>
									<?php if ($planty_price) : ?>
										<span class="hero__price"><?php echo esc_html($planty_price); ?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="hero__media">
								<img src="<?php echo esc_url($planty_image); ?>" alt=""
									class="hero__image" />
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
		<div class="hero__container hero__container--pagination">
			<div class="hero__pagination"></div>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</section>