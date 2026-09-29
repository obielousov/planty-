<?php
/**
 * Reviews section.
 *
 * @package planty
 */

$planty_reviews_title = get_theme_mod('planty_reviews_title', 'What Our Customers Say ?');

$planty_reviews_query = new WP_Query(
	array(
		'post_type'      => 'review',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);
?>
<section class="reviews">
	<div class="reviews__container">
		<h2 class="section__title reviews__title"><?php echo esc_html($planty_reviews_title); ?></h2>
		<?php if ($planty_reviews_query->have_posts()) : ?>
			<div class="reviews__slider swiper">
				<div class="reviews__wrapper swiper-wrapper">
					<?php while ($planty_reviews_query->have_posts()) : $planty_reviews_query->the_post(); ?>
						<?php
						$planty_review_photo = get_the_post_thumbnail_url(null, 'medium');

						if (empty($planty_review_photo)) {
							$planty_review_photo = planty_asset('img/reviews/reviews01.jpg');
						}
						?>
						<div class="reviews__slide swiper-slide">
							<blockquote class="reviews-slide__text">
								<?php the_content(); ?>
							</blockquote>
							<div class="reviews-slide__person">
								<div class="reviews-slide__media">
									<img src="<?php echo esc_url($planty_review_photo); ?>" alt="" />
								</div>
								<div class="reviews-slide__content">
									<h3 class="reviews-slide__value"><?php the_title(); ?></h3>
									<div class="reviews-slide__rating" role="img" aria-label="Rated 4 out of 5">
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>"
											alt="" />
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>"
											alt="" />
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>"
											alt="" />
										<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>"
											alt="" />
									</div>
								</div>
							</div>
							<div class="reviews-slide__decor">
								<img src="<?php echo esc_url(planty_asset('img/layers/flower-layer.jpg')); ?>"
									alt="" />
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				<div class="reviews__pagination swiper-pagination"></div>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</div>
</section>
