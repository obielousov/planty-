<?php

/**
 * Custom post types.
 *
 * @package planty
 */

/**
 * Register the review post type.
 *
 * Reviews only show up inside the reviews slider, so they get no public URLs
 * and no archive page. Order is set by hand through the "Order" field.
 */
function planty_register_post_types()
{
	register_post_type(
		'review',
		array(
			'labels'       => array(
				'name'          => esc_html__('Reviews', 'planty'),
				'singular_name' => esc_html__('Review', 'planty'),
				'add_new_item'  => esc_html__('Add New Review', 'planty'),
				'edit_item'     => esc_html__('Edit Review', 'planty'),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'planty_slide',
		array(
			'labels'       => array(
				'name'          => esc_html__('Slides', 'planty'),
				'singular_name' => esc_html__('Slide', 'planty'),
				'add_new_item'  => esc_html__('Add New Slide', 'planty'),
				'edit_item'     => esc_html__('Edit Slide', 'planty'),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-images-alt2',
			'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'planty_plant_slide',
		array(
			'labels'       => array(
				'name'          => esc_html__('Plant Slides', 'planty'),
				'singular_name' => esc_html__('Plant Slide', 'planty'),
				'add_new_item'  => esc_html__('Add New Plant Slide', 'planty'),
				'edit_item'     => esc_html__('Edit Plant Slide', 'planty'),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-format-image',
			'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'planty_deal_slide',
		array(
			'labels'       => array(
				'name'          => esc_html__('Deal Slides', 'planty'),
				'singular_name' => esc_html__('Deal Slide', 'planty'),
				'add_new_item'  => esc_html__('Add New Deal Slide', 'planty'),
				'edit_item'     => esc_html__('Edit Deal Slide', 'planty'),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-tag',
			'supports'     => array('title', 'thumbnail', 'page-attributes'),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'planty_product',
		array(
			'labels'       => array(
				'name'          => esc_html__('Products', 'planty'),
				'singular_name' => esc_html__('Product', 'planty'),
				'add_new_item'  => esc_html__('Add New Product', 'planty'),
				'edit_item'     => esc_html__('Edit Product', 'planty'),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-cart',
			'supports'     => array('title', 'thumbnail', 'page-attributes'),
			'show_in_rest' => true,
		)
	);
}
add_action('init', 'planty_register_post_types');

/**
 * Price field for a hero slide.
 *
 * The design shows the price as a whole string ("New Price : $ 30"), so it is
 * stored as free text rather than as a number.
 */
function planty_slide_price_box()
{
	add_meta_box(
		'planty_slide_price',
		esc_html__('Price', 'planty'),
		'planty_slide_price_box_html',
		'planty_slide',
		'side'
	);
}
add_action('add_meta_boxes', 'planty_slide_price_box');

/**
 * Markup of the price field.
 *
 * @param WP_Post $post Post being edited.
 */
function planty_slide_price_box_html($post)
{
	$planty_price = get_post_meta($post->ID, 'planty_slide_price', true);

	wp_nonce_field('planty_slide_price_save', 'planty_slide_price_nonce');
	?>
	<label class="screen-reader-text" for="planty_slide_price">
		<?php esc_html_e('Price', 'planty'); ?>
	</label>
	<input type="text" id="planty_slide_price" name="planty_slide_price"
		value="<?php echo esc_attr($planty_price); ?>" class="widefat" />
	<?php
}

/**
 * Save the price field.
 *
 * @param int $post_id Post being saved.
 */
function planty_slide_price_save($post_id)
{
	if (!isset($_POST['planty_slide_price_nonce'])) {
		return;
	}

	$planty_nonce = sanitize_key(wp_unslash($_POST['planty_slide_price_nonce']));

	if (!wp_verify_nonce($planty_nonce, 'planty_slide_price_save')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (!isset($_POST['planty_slide_price'])) {
		return;
	}

	$planty_price = sanitize_text_field(wp_unslash($_POST['planty_slide_price']));

	update_post_meta($post_id, 'planty_slide_price', $planty_price);
}
add_action('save_post_planty_slide', 'planty_slide_price_save');

/**
 * Details of a deal slide: both prices, the countdown deadline and the link.
 *
 * The prices stay free text because the design shows them as whole strings
 * ("$ 320"), and the deadline keeps the exact shape a datetime-local input
 * produces, so it can be read back into that input without any conversion.
 */
function planty_deal_slide_box()
{
	add_meta_box(
		'planty_deal_slide_details',
		esc_html__('Deal Details', 'planty'),
		'planty_deal_slide_box_html',
		'planty_deal_slide',
		'normal'
	);
}
add_action('add_meta_boxes', 'planty_deal_slide_box');

/**
 * Markup of the deal slide fields.
 *
 * @param WP_Post $post Post being edited.
 */
function planty_deal_slide_box_html($post)
{
	$planty_price_old = get_post_meta($post->ID, 'planty_deal_price_old', true);
	$planty_price_new = get_post_meta($post->ID, 'planty_deal_price_new', true);
	$planty_deadline  = get_post_meta($post->ID, 'planty_deal_deadline', true);
	$planty_link      = get_post_meta($post->ID, 'planty_deal_link', true);

	wp_nonce_field('planty_deal_slide_save', 'planty_deal_slide_nonce');
	?>
	<p>
		<label for="planty_deal_price_old"><?php esc_html_e('Old price', 'planty'); ?></label><br />
		$&nbsp;<input type="text" id="planty_deal_price_old" name="planty_deal_price_old"
			value="<?php echo esc_attr($planty_price_old); ?>" class="small-text" placeholder="320" />
	</p>
	<p>
		<label for="planty_deal_price_new"><?php esc_html_e('New price', 'planty'); ?></label><br />
		$&nbsp;<input type="text" id="planty_deal_price_new" name="planty_deal_price_new"
			value="<?php echo esc_attr($planty_price_new); ?>" class="small-text" placeholder="280" />
	</p>
	<p>
		<label for="planty_deal_deadline"><?php esc_html_e('Countdown deadline', 'planty'); ?></label><br />
		<input type="datetime-local" id="planty_deal_deadline" name="planty_deal_deadline"
			value="<?php echo esc_attr($planty_deadline); ?>" class="widefat" />
	</p>
	<p class="description">
		<?php esc_html_e('The moment the countdown runs down to, in the local time of the site.', 'planty'); ?>
	</p>
	<p>
		<label for="planty_deal_link"><?php esc_html_e('Link', 'planty'); ?></label><br />
		<input type="url" id="planty_deal_link" name="planty_deal_link"
			value="<?php echo esc_attr($planty_link); ?>" class="widefat" placeholder="#" />
	</p>
	<?php
}

/**
 * Save the deal slide fields.
 *
 * @param int $post_id Post being saved.
 */
function planty_deal_slide_save($post_id)
{
	if (!isset($_POST['planty_deal_slide_nonce'])) {
		return;
	}

	$planty_nonce = sanitize_key(wp_unslash($_POST['planty_deal_slide_nonce']));

	if (!wp_verify_nonce($planty_nonce, 'planty_deal_slide_save')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	$planty_prices = array('planty_deal_price_old', 'planty_deal_price_new');

	foreach ($planty_prices as $planty_key) {
		if (!isset($_POST[$planty_key])) {
			continue;
		}

		update_post_meta(
			$post_id,
			$planty_key,
			sanitize_text_field(wp_unslash($_POST[$planty_key]))
		);
	}

	if (isset($_POST['planty_deal_link'])) {
		update_post_meta(
			$post_id,
			'planty_deal_link',
			esc_url_raw(wp_unslash($_POST['planty_deal_link']))
		);
	}

	// A datetime-local input only ever submits "2027-02-15T00:00", so anything
	// that does not match that shape is dropped rather than stored.
	if (isset($_POST['planty_deal_deadline'])) {
		$planty_deadline = sanitize_text_field(wp_unslash($_POST['planty_deal_deadline']));

		if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $planty_deadline)) {
			update_post_meta($post_id, 'planty_deal_deadline', $planty_deadline);
		}
	}
}
add_action('save_post_planty_deal_slide', 'planty_deal_slide_save');

function planty_register_taxonomies()
{
	register_taxonomy(
		'planty_product_category',
		array('planty_product'),
		array(
			'labels'            => array(
				'name'          => esc_html__('Product Categories', 'planty'),
				'singular_name' => esc_html__('Product Category', 'planty'),
				'add_new_item'  => esc_html__('Add New Product Category', 'planty'),
				'edit_item'     => esc_html__('Edit Product Category', 'planty'),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
		)
	);
}
add_action('init', 'planty_register_taxonomies');

function planty_product_box()
{
	add_meta_box(
		'planty_product_details',
		esc_html__('Product Details', 'planty'),
		'planty_product_box_html',
		'planty_product',
		'normal'
	);
}
add_action('add_meta_boxes', 'planty_product_box');

function planty_product_box_html($post)
{
	$planty_price_old = get_post_meta($post->ID, 'planty_product_price_old', true);
	$planty_price_new = get_post_meta($post->ID, 'planty_product_price_new', true);
	$planty_link      = get_post_meta($post->ID, 'planty_product_link', true);

	wp_nonce_field('planty_product_save', 'planty_product_nonce');
	?>
	<p>
		<label for="planty_product_price_old"><?php esc_html_e('Old price', 'planty'); ?></label><br />
		$&nbsp;<input type="text" id="planty_product_price_old" name="planty_product_price_old"
			value="<?php echo esc_attr($planty_price_old); ?>" class="small-text" placeholder="320" />
	</p>
	<p>
		<label for="planty_product_price_new"><?php esc_html_e('New price', 'planty'); ?></label><br />
		$&nbsp;<input type="text" id="planty_product_price_new" name="planty_product_price_new"
			value="<?php echo esc_attr($planty_price_new); ?>" class="small-text" placeholder="280" />
	</p>
	<p>
		<label for="planty_product_link"><?php esc_html_e('Link', 'planty'); ?></label><br />
		<input type="url" id="planty_product_link" name="planty_product_link"
			value="<?php echo esc_attr($planty_link); ?>" class="widefat" placeholder="#" />
	</p>
	<?php
}

function planty_product_save($post_id)
{
	if (!isset($_POST['planty_product_nonce'])) {
		return;
	}

	$planty_nonce = sanitize_key(wp_unslash($_POST['planty_product_nonce']));

	if (!wp_verify_nonce($planty_nonce, 'planty_product_save')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	$planty_prices = array('planty_product_price_old', 'planty_product_price_new');

	foreach ($planty_prices as $planty_key) {
		if (!isset($_POST[$planty_key])) {
			continue;
		}

		update_post_meta(
			$post_id,
			$planty_key,
			sanitize_text_field(wp_unslash($_POST[$planty_key]))
		);
	}

	if (isset($_POST['planty_product_link'])) {
		update_post_meta(
			$post_id,
			'planty_product_link',
			esc_url_raw(wp_unslash($_POST['planty_product_link']))
		);
	}
}
add_action('save_post_planty_product', 'planty_product_save');
