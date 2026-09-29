<?php

/*
 * @package planty
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<a class="skip-link" href="#primary">Skip to content</a>
	<div class="wrapper">
		<header class="header">
			<div class="header__container">
				<a class="header__logo" href="<?php echo esc_url(home_url('/')); ?>">
					<?php
					$planty_logo_id = get_theme_mod('custom_logo');
					if ($planty_logo_id) {
						$planty_logo_url = wp_get_attachment_image_url($planty_logo_id, 'full');
					} else {
						$planty_logo_url = planty_asset(
							'img/logo.svg'
						);
					}
					?>
					<img src="<?php echo esc_url($planty_logo_url); ?>" alt="" />
					<div class="header__text-logo">
						<p>Planty</p>
					</div>
				</a>
				<div class="header__menu menu">
					<button class="menu__burger" type="button" aria-label="Open menu" aria-expanded="false"
						aria-controls="menu-body">
						<span></span>
					</button>
					<nav class="menu__body" id="menu-body" aria-label="Primary">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'container' => false,
								'menu_class' => 'menu__list',
								'fallback_cb' => false,
								'depth' => 1,
							)
						);
						?>
						<div class="header__actions">
							<a href="#" class="header__link-actions">login</a>
							<a href="#" class="header__link-actions">register</a>
						</div>
					</nav>
					<div class="menu__overlay"></div>
				</div>
			</div>
		</header>