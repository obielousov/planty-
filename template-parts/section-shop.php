<?php

$planty_shop_title = get_theme_mod('planty_shop_title');

if (empty($planty_shop_title)) {
	$planty_shop_title = 'Shop By Category';
}

$planty_shop_terms = get_terms(
	array(
		'taxonomy' => 'planty_product_category',
		'hide_empty' => true,
		'orderby' => 'term_id',
		'order' => 'ASC',
	)
);

if (is_wp_error($planty_shop_terms)) {
	$planty_shop_terms = array();
}
?>
<section class="shop">
	<div class="shop__container">
		<h2 class="section__title shop__title"><?php echo esc_html($planty_shop_title); ?></h2>
		<?php if ($planty_shop_terms) : ?>
			<div class="shop__tabs tabs">
				<div class="tabs__navigation" role="tablist" aria-label="Product categories">
					<?php foreach ($planty_shop_terms as $planty_key => $planty_term) : ?>
						<button type="button" role="tab"
							id="planty-tab-<?php echo esc_attr($planty_term->slug); ?>"
							aria-controls="planty-panel-<?php echo esc_attr($planty_term->slug); ?>"
							aria-selected="<?php echo 0 === $planty_key ? 'true' : 'false'; ?>"
							class="tabs__title<?php echo 0 === $planty_key ? ' tabs__title--active' : ''; ?>"
							data-tab="<?php echo esc_attr($planty_term->slug); ?>">
							<?php echo esc_html($planty_term->name); ?>
						</button>
					<?php endforeach; ?>
				</div>
				<div class="tabs__content">
					<?php foreach ($planty_shop_terms as $planty_key => $planty_term) : ?>
						<div class="tabs__body" role="tabpanel" tabindex="-1"
							id="planty-panel-<?php echo esc_attr($planty_term->slug); ?>"
							aria-labelledby="planty-tab-<?php echo esc_attr($planty_term->slug); ?>"
							data-tab-content="<?php echo esc_attr($planty_term->slug); ?>"<?php echo 0 === $planty_key ? '' : ' hidden'; ?>>
							<?php
							$planty_products = new WP_Query(
								array(
									'post_type' => 'planty_product',
									'posts_per_page' => 8,
									'paged' => 1,
									'orderby' => 'menu_order',
									'order' => 'ASC',
									'tax_query' => array(
										array(
											'taxonomy' => 'planty_product_category',
											'field' => 'term_id',
											'terms' => $planty_term->term_id,
										),
									),
								)
							);

							while ($planty_products->have_posts()) :
								$planty_products->the_post();

								planty_product_card(get_the_ID());
							endwhile;

							wp_reset_postdata();

							planty_shop_pagination($planty_products, $planty_term->slug);
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<p class="shop__status screen-reader-text" aria-live="polite"></p>
		<?php endif; ?>
	</div>
</section>
