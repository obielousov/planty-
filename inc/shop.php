<?php

function planty_product_card($post_id)
{
	$planty_image = get_the_post_thumbnail_url($post_id, 'large');

	if (empty($planty_image)) {
		$planty_image = planty_asset('img/shop/shop-01.jpg');
	}

	$planty_price_old = planty_price(get_post_meta($post_id, 'planty_product_price_old', true));
	$planty_price_new = planty_price(get_post_meta($post_id, 'planty_product_price_new', true));
	?>
	<div class="body-tabs__item item-tabs">
		<div class="item-tabs__media">
			<a href="#" class="item-tabs__link-image" aria-hidden="true" tabindex="-1">
				<img class="item-tabs__image" src="<?php echo esc_url($planty_image); ?>" alt="" />
			</a>
		</div>
		<div class="item-tabs__content">
			<h3 class="item-tabs__title">
				<a href="#" class="item-tabs__link-title"><?php echo esc_html(get_the_title($post_id)); ?></a>
			</h3>
			<div class="item-tabs__rating" role="img" aria-label="Rated 4 out of 5">
				<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
				<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
				<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
				<img src="<?php echo esc_url(planty_asset('img/icons/star.svg')); ?>" alt="" />
			</div>
			<div class="item-tabs__price">
				<?php if ($planty_price_old) : ?>
					<span class="item-tabs__value item-tabs__value--old"><?php echo esc_html($planty_price_old); ?></span>
				<?php endif; ?>
				<?php if ($planty_price_new) : ?>
					<span class="item-tabs__value"><?php echo esc_html($planty_price_new); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}

function planty_shop_pagination($query, $term_slug, $current = 1)
{
	if ($query->max_num_pages < 2) {
		return;
	}
	?>
	<nav class="shop__pagination" aria-label="Products pagination"
		data-pagination="<?php echo esc_attr($term_slug); ?>">
		<?php
		echo paginate_links(
			array(
				'total' => $query->max_num_pages,
				'current' => $current,
				'type' => 'plain',
				'prev_next' => false,
			)
		);
		?>
	</nav>
	<?php
}

function planty_shop_rest_products($request)
{
	$planty_term = get_term_by('slug', $request->get_param('term'), 'planty_product_category');

	if (!$planty_term) {
		return new WP_Error('planty_shop_no_term', 'Unknown category', array('status' => 404));
	}

	$planty_page = max(1, (int) $request->get_param('page'));

	$planty_products = new WP_Query(
		array(
			'post_type' => 'planty_product',
			'posts_per_page' => 8,
			'paged' => $planty_page,
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

	ob_start();

	while ($planty_products->have_posts()) {
		$planty_products->the_post();

		planty_product_card(get_the_ID());
	}

	wp_reset_postdata();

	$planty_items = ob_get_clean();

	ob_start();

	planty_shop_pagination($planty_products, $planty_term->slug, $planty_page);

	$planty_pagination = ob_get_clean();

	return rest_ensure_response(
		array(
			'items' => $planty_items,
			'pagination' => $planty_pagination,
		)
	);
}

function planty_register_shop_rest()
{
	register_rest_route(
		'planty/v1',
		'/products',
		array(
			'methods' => 'GET',
			'callback' => 'planty_shop_rest_products',
			'permission_callback' => '__return_true',
			'args' => array(
				'term' => array(
					'required' => true,
					'sanitize_callback' => 'sanitize_title',
				),
				'page' => array(
					'default' => 1,
					'sanitize_callback' => 'absint',
				),
			),
		)
	);
}
add_action('rest_api_init', 'planty_register_shop_rest');
