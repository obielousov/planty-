<?php

/**
 * planty Theme Customizer
 *
 * @package planty
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function planty_customize_register($wp_customize)
{
	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'planty_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'planty_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_section(
		'planty_hero',
		array(
			'title'    => __('Hero', 'planty'),
			'priority' => 110,
		)
	);

	$planty_hero_texts = array(
		'planty_hero_subtitle' => array(
			'label' => __('Subtitle', 'planty'),
			'default' => 'Let’s Bring',
		),
		'planty_hero_link_text' => array(
			'label' => __('Link text', 'planty'),
			'default' => 'Shop Now',
		),
	);

	foreach ($planty_hero_texts as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			$planty_id,
			array(
				'label' => $planty_field['label'],
				'section' => 'planty_hero',
				'type' => 'text',
			)
		);
	}

	$wp_customize->add_setting(
		'planty_hero_link',
		array(
			'default' => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'planty_hero_link',
		array(
			'label' => __('Link', 'planty'),
			'section' => 'planty_hero',
			'type' => 'url',
		)
	);

	$wp_customize->add_section(
		'planty_footer',
		array(
			'title'    => __('Footer', 'planty'),
			'priority' => 120,
		)
	);

	$wp_customize->add_setting(
		'planty_footer_text',
		array(
			'default'           => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam atque delectus ipsam! Eligendi ipsa quas sapiente dolorem, accusantium earum impedit illum. Enim nostrum saepe beatae, nisi dolore quae laborum facilis',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'planty_footer_text',
		array(
			'label'   => __('Footer description', 'planty'),
			'section' => 'planty_footer',
			'type'    => 'textarea',
		)
	);

	$planty_footer_texts = array(
		'planty_footer_logo_text' => array(
			'label' => __('Logo Text', 'planty'),
			'default' => 'Planty',
		),
		'planty_footer_links_title' => array(
			'label' => __('Quick Links title', 'planty'),
			'default' => 'Quick Links',
		),
		'planty_footer_newsletter_title' => array(
			'label' => __('News Letter title', 'planty'),
			'default' => 'News Letter',
		),
		'planty_footer_form_placeholder' => array(
			'label' => __('Form placeholder', 'planty'),
			'default' => 'Enter Email Address',
		),
		'planty_footer_form_button' => array(
			'label' => __('Form button', 'planty'),
			'default' => 'Subscribe',
		),
		'planty_footer_form_note' => array(
			'label' => __('Form note', 'planty'),
			'default' => 'We never spam you!',
		),
		'planty_footer_contact_title' => array(
			'label' => __('Contact title', 'planty'),
			'default' => 'Contact',
		),
		'planty_footer_address_text' => array(
			'label' => __('Address text', 'planty'),
			'default' => '121 King St, Melbourne den 3000, Australia',
		),
		'planty_footer_email_text' => array(
			'label' => __('Email text', 'planty'),
			'default' => 'Info@example.com',
		),
		'planty_footer_phone_text' => array(
			'label' => __('Phone text', 'planty'),
			'default' => '+91 999999999',
		),

	);
	foreach ($planty_footer_texts as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			$planty_id,
			array(
				'label' => $planty_field['label'],
				'section' => 'planty_footer',
				'type' => 'text',
			)
		);
	}

	$planty_footer_socials = array(
		'planty_footer_social_fb' => array(
			'label' => __('Facebook link', 'planty'),
			'default' => '#',
		),
		'planty_footer_social_inn' => array(
			'label' => __('LinkedIn link', 'planty'),
			'default' => '#',
		),
		'planty_footer_social_inst' => array(
			'label' => __('Instagram link', 'planty'),
			'default' => '#',
		),
		'planty_footer_social_pintarest' => array(
			'label' => __('Pinterest link', 'planty'),
			'default' => '#',
		),
		'planty_footer_social_x' => array(
			'label' => __('X link', 'planty'),
			'default' => '#',
		),

	);

	foreach ($planty_footer_socials as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			$planty_id,
			array(
				'label' => $planty_field['label'],
				'section' => 'planty_footer',
				'type' => 'url',
			)
		);
	}

	$planty_footer_links = array(
		'planty_footer_address_link' => array(
			'label' => __('Address link', 'planty'),
			'default' => '#',
		),
		'planty_footer_email_link' => array(
			'label' => __('Email link', 'planty'),
			'default' => 'mailto:Info@example.com',
		),
		'planty_footer_phone_link' => array(
			'label' => __('Phone link', 'planty'),
			'default' => 'tel:+91999999999',
		),
	);

	foreach ($planty_footer_links as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			$planty_id,
			array(
				'label' => $planty_field['label'],
				'section' => 'planty_footer',
				'type' => 'url',
			)
		);
	}

	$wp_customize->add_setting(
		'planty_footer_logo',
		array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'planty_footer_logo',
			array(
				'label' => __('Footer logo', 'planty'),
				'section' => 'planty_footer',
			)
		)
	);

	$wp_customize->add_section(
		'planty_collection',
		array(
			'title'    => __('Collection', 'planty'),
			'priority' => 130,
		)
	);

	$wp_customize->add_setting(
		'planty_collection_title',
		array(
			'default'           => 'Plant Collection',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'planty_collection_title',
		array(
			'label'   => __('Section title', 'planty'),
			'section' => 'planty_collection',
			'type'    => 'text',
		)
	);

	$planty_collection_texts = array(
		'planty_collection_1_title' => array(
			'label' => __('Card 1 title', 'planty'),
			'default' => 'Indoor Plants',
		),
		'planty_collection_1_link_text' => array(
			'label' => __('Card 1 link text', 'planty'),
			'default' => 'Shop Now',
		),
		'planty_collection_2_title' => array(
			'label' => __('Card 2 title', 'planty'),
			'default' => 'Outdoor Plants',
		),
		'planty_collection_2_link_text' => array(
			'label' => __('Card 2 link text', 'planty'),
			'default' => 'Shop Now',
		),
	);

	foreach ($planty_collection_texts as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			$planty_id,
			array(
				'label' => $planty_field['label'],
				'section' => 'planty_collection',
				'type' => 'text',
			)
		);
	}

	$planty_collection_links = array(
		'planty_collection_1_link' => array(
			'label' => __('Card 1 link', 'planty'),
			'default' => '#',
		),
		'planty_collection_2_link' => array(
			'label' => __('Card 2 link', 'planty'),
			'default' => '#',
		),
	);

	foreach ($planty_collection_links as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			$planty_id,
			array(
				'label' => $planty_field['label'],
				'section' => 'planty_collection',
				'type' => 'url',
			)
		);
	}

	$planty_collection_images = array(
		'planty_collection_1_image' => array(
			'label' => __('Card 1 image', 'planty'),
			'default' => planty_asset('img/collection/collection01.jpg'),
		),
		'planty_collection_2_image' => array(
			'label' => __('Card 2 image', 'planty'),
			'default' => planty_asset('img/collection/collection02.jpg'),
		),
	);

	foreach ($planty_collection_images as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$planty_id,
				array(
					'label' => $planty_field['label'],
					'section' => 'planty_collection',
				)
			)
		);
	}

	$wp_customize->add_section(
		'planty_reviews',
		array(
			'title'    => __('Reviews', 'planty'),
			'priority' => 140,
		)
	);

	$wp_customize->add_setting(
		'planty_reviews_title',
		array(
			'default'           => 'What Our Customers Say ?',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'planty_reviews_title',
		array(
			'label'   => __('Section title', 'planty'),
			'section' => 'planty_reviews',
			'type'    => 'text',
		)
	);

	$wp_customize->add_section(
		'planty_find',
		array(
			'title'    => __('Find', 'planty'),
			'priority' => 150,
		)
	);

	$wp_customize->add_setting(
		'planty_find_title',
		array(
			'default'           => 'Find A Perfect Plant For Your Home',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'planty_find_title',
		array(
			'label'   => __('Title', 'planty'),
			'section' => 'planty_find',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'planty_find_link_text',
		array(
			'default'           => 'Get Started',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'planty_find_link_text',
		array(
			'label'   => __('Link text', 'planty'),
			'section' => 'planty_find',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'planty_find_link',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'planty_find_link',
		array(
			'label'   => __('Link', 'planty'),
			'section' => 'planty_find',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'planty_find_image',
		array(
			'default'           => planty_asset('img/bg/bg-plant.jpg'),
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'planty_find_image',
			array(
				'label'   => __('Background image', 'planty'),
				'section' => 'planty_find',
			)
		)
	);

	$wp_customize->add_section(
		'planty_living',
		array(
			'title' => __('Living', 'planty'),
			'priority' => 160,
		)
	);
	$planty_living_texts = array(
		'planty_living_title' => array(
			'label' => __('Title', 'planty'),
			'default' => 'LIVING THING GROWS',
		),
		'planty_living_price' => array(
			'label' => __('Price', 'planty'),
			'default' => 'Price From $ 45.00',
		),
		'planty_living_link_text' => array(
			'label' => __('Link text', 'planty'),
			'default' => 'Shop Now',
		),

	);

	foreach ($planty_living_texts as $planty_id => $planty_field) {
		$wp_customize->add_setting(
			$planty_id,
			array(
				'default' => $planty_field['default'],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			$planty_id,
			array(
				'label' => $planty_field['label'],
				'section' => 'planty_living',
				'type' => 'text',
			)
		);
	}
	$wp_customize->add_setting(
		'planty_living_text',
		array(
			'default' => 'You have no plants & are you ready to grow ? come and shop with us.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'planty_living_text',
		array(
			'label' => __('Text', 'planty'),
			'section' => 'planty_living',
			'type' => 'textarea',
		)
	);
	$wp_customize->add_setting(
		'planty_living_link',
		array(
			'default' => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'planty_living_link',
		array(
			'label' => __('Link', 'planty'),
			'section' => 'planty_living',
			'type' => 'url',
		)
	);

	$wp_customize->add_section(
		'planty_plant',
		array(
			'title' => __('Plant', 'planty'),
			'priority' => 165,
		)
	);

	$wp_customize->add_setting(
		'planty_plant_link_text',
		array(
			'default' => 'Get More Information',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'planty_plant_link_text',
		array(
			'label' => __('Link text', 'planty'),
			'section' => 'planty_plant',
			'type' => 'text',
		)
	);

	$wp_customize->add_setting(
		'planty_plant_link',
		array(
			'default' => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'planty_plant_link',
		array(
			'label' => __('Link', 'planty'),
			'section' => 'planty_plant',
			'type' => 'url',
		)
	);

	$wp_customize->add_section(
		'planty_deal',
		array(
			'title'    => __('Deal', 'planty'),
			'priority' => 170,
		)
	);

	$wp_customize->add_setting(
		'planty_deal_title',
		array(
			'default' => 'Deal Of The Day',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'planty_deal_title',
		array(
			'label' => __('Section title', 'planty'),
			'section' => 'planty_deal',
			'type' => 'text',
		)
	);

	$wp_customize->add_setting(
		'planty_deal_image',
		array(
			'default' => planty_asset('img/plant/deal.jpg'),
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'planty_deal_image',
			array(
				'label' => __('Image', 'planty'),
				'section' => 'planty_deal',
			)
		)
	);

	$wp_customize->add_section(
		'planty_shop',
		array(
			'title'    => __('Shop', 'planty'),
			'priority' => 180,
		)
	);

	$wp_customize->add_setting(
		'planty_shop_title',
		array(
			'default' => 'Shop By Category',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'planty_shop_title',
		array(
			'label' => __('Section title', 'planty'),
			'section' => 'planty_shop',
			'type' => 'text',
		)
	);
}
add_action('customize_register', 'planty_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function planty_customize_partial_blogname()
{
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function planty_customize_partial_blogdescription()
{
	bloginfo('description');
}