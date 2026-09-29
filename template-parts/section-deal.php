<?php

/**
 * Deal Of The Day section. The section title and the large image next to the
 * slider are shared and come from the planty_deal Customizer section, the
 * slides themselves come from the planty_deal_slide post type.
 */

$planty_deal_title = get_theme_mod('planty_deal_title');

// get_theme_mod falls back only when nothing is stored at all, so a value
// saved as an empty string in the Customizer has to be caught here as well
if (empty($planty_deal_title)) {
	$planty_deal_title = 'Deal Of The Day';
}

$planty_deal_image = get_theme_mod('planty_deal_image');

if (empty($planty_deal_image)) {
	$planty_deal_image = planty_asset('img/plant/deal.jpg');
}

// Slides are ordered by the "Order" field in the editor, so the client decides
// which one the slider opens with
$planty_deal_slides = new WP_Query(
	array(
		'post_type' => 'planty_deal_slide',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'no_found_rows' => true,
	)
);
?>
<section class="deal">
	<div class="deal__inner">
		<h2 class="section__title deal__title"><?php echo esc_html($planty_deal_title); ?></h2>
		<div class="deal__content">
			<div class="deal__media">
				<img src="<?php echo esc_url($planty_deal_image); ?>" alt="plant image" class="deal__image" />
			</div>
			<?php if ($planty_deal_slides->have_posts()) : ?>
				<div class="deal__slider swiper">
					<div class="deal__wrapper swiper-wrapper">
						<?php while ($planty_deal_slides->have_posts()) : ?>
							<?php
							$planty_deal_slides->the_post();

							$planty_image = get_the_post_thumbnail_url(null, 'large');

							if (empty($planty_image)) {
								$planty_image = planty_asset('img/plant/slide-plant01.png');
							}

							$planty_price_old = get_post_meta(get_the_ID(), 'planty_deal_price_old', true);
							$planty_price_new = get_post_meta(get_the_ID(), 'planty_deal_price_new', true);
							$planty_deadline  = get_post_meta(get_the_ID(), 'planty_deal_deadline', true);

							// The editor holds digits only, so the currency symbol is added
							// here and a symbol typed by hand does not show up twice
							$planty_price_old = preg_replace('/[^0-9.,]/', '', $planty_price_old);
							$planty_price_new = preg_replace('/[^0-9.,]/', '', $planty_price_new);
							$planty_price_old = $planty_price_old ? '$ ' . $planty_price_old : '';
							$planty_price_new = $planty_price_new ? '$ ' . $planty_price_new : '';
							?>
							<div class="deal__slide swiper-slide" data-deadline="<?php echo esc_attr($planty_deadline); ?>">
								<div class="deal-slide__media">
									<a href="#" class="deal-slide__link-image" aria-hidden="true" tabindex="-1">
										<img src="<?php echo esc_url($planty_image); ?>" alt="" class="deal-slide__image" />
									</a>
								</div>
								<div class="deal-slide__body body-slide">
									<h3 class="body-slide__title">
										<a href="#" class="body-slide__link-title"><?php the_title(); ?></a>
									</h3>
									<div class="body-slide__price">
										<?php if ($planty_price_old) : ?>
											<span
												class="body-slide__value body-slide__value--gray"><?php echo esc_html($planty_price_old); ?></span>
										<?php endif; ?>
										<?php if ($planty_price_new) : ?>
											<span class="body-slide__value"><?php echo esc_html($planty_price_new); ?></span>
										<?php endif; ?>
									</div>
									<div class="body-slide__rating" role="img" aria-label="Rated 4 out of 5">
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
									</div>
								</div>
								<?php if ($planty_deadline) : ?>
									<div class="deal-slide__counter counter-slide">
										<div class="counter-slide__item">
											<span class="counter-slide__value" data-counter="days">00</span>
											<span class="counter-slide__label">Days</span>
										</div>
										<div class="counter-slide__item">
											<span class="counter-slide__value" data-counter="hours">00</span>
											<span class="counter-slide__label">Hours</span>
										</div>
										<div class="counter-slide__item">
											<span class="counter-slide__value" data-counter="minutes">00</span>
											<span class="counter-slide__label">Min</span>
										</div>
										<div class="counter-slide__item">
											<span class="counter-slide__value" data-counter="seconds">00</span>
											<span class="counter-slide__label">Sec</span>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php endwhile; ?>
					</div>
					<div class="deal__pagination swiper-pagination"></div>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</div>
</section>