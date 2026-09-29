<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package planty
 */

?>
<footer class="footer">
	<div class="footer__container">
		<div class="footer__column">
			<div class="footer__logo">
				<?php
				$planty_footer_logo = get_theme_mod('planty_footer_logo');

				if (empty($planty_footer_logo)) {
					$planty_footer_logo = planty_asset('img/icons/logo-footer.svg');
				}
				?>
				<img src="<?php echo esc_url($planty_footer_logo); ?>" alt="image" />
				<div class="footer__text-logo">
					<p><?php echo esc_html(get_theme_mod(
							'planty_footer_logo_text',
							'Planty'
						)); ?></p>
				</div>
			</div>
			<div class="footer__text">
				<?php
				$planty_footer_text = get_theme_mod(
					'planty_footer_text',
					'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam atque delectus ipsam! Eligendi ipsa quas sapiente dolorem, accusantium earum impedit illum. Enim nostrum saepe beatae, nisi dolore quae laborum facilis'
				);
				?>
				<p><?php echo esc_html($planty_footer_text); ?></p>
			</div>
			<div class="footer__social social-footer">
				<ul class="social-footer__list">
					<li class="social-footer__item">
						<a href="<?php echo esc_url(get_theme_mod('planty_footer_social_fb', '#')); ?>"
							class="social-footer__link-item" aria-label="Facebook">
							<img src="<?php echo esc_url(planty_asset('/img/icons/fb.svg')); ?>" alt="" />
						</a>
					</li>
					<li class="social-footer__item">
						<a href="<?php echo esc_url(get_theme_mod('planty_footer_social_inn', '#')); ?>"
							class="social-footer__link-item" aria-label="LinkedIn">
							<img src="<?php echo esc_url(planty_asset('/img/icons/inn.svg')); ?>" alt="" />
						</a>
					</li>
					<li class="social-footer__item">
						<a href="<?php echo esc_url(get_theme_mod('planty_footer_social_inst', '#')); ?>"
							class="social-footer__link-item" aria-label="Instagram">
							<img src="<?php echo esc_url(planty_asset('/img/icons/inst.svg')); ?> " alt="" />
						</a>
					</li>
					<li class="social-footer__item">
						<a href="<?php echo esc_url(get_theme_mod('planty_footer_social_pintarest', '#')); ?>"
							class="social-footer__link-item" aria-label="Pinterest">
							<img src="<?php echo esc_url(planty_asset('/img/icons/pintarest.svg')); ?> " alt="" />
						</a>
					</li>
					<li class="social-footer__item">
						<a href="<?php echo esc_url(get_theme_mod('planty_footer_social_x', '#')); ?>"
							class="social-footer__link-item" aria-label="X">
							<img src="<?php echo esc_url(planty_asset('/img/icons/x.svg')); ?>" alt="" />
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="footer__column">
			<h2 class="footer__label">
				<?php echo esc_html(get_theme_mod('planty_footer_links_title', 'Quick Links')); ?>
			</h2>
			<?php wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container' => 'nav',
					'container_class' => 'footer__nav',
					'container_aria_label' => 'Footer',
					'menu_class' => 'footer__list',
					'fallback_cb' => false,
					'depth' => 1,
				)
			);
			?>
		</div>
		<div class="footer__column">
			<h2 class="footer__label">
				<?php echo esc_html(get_theme_mod('planty_footer_newsletter_title', 'News Letter')); ?></h2>
			<form action="#" class="footer__form form">
				<div class="form__field">
					<label class="screen-reader-text" for="planty-footer-email">Email address</label>
					<input required type="email" id="planty-footer-email" name="email" autocomplete="email"
						aria-describedby="planty-footer-note" placeholder="<?php echo
																			esc_attr(get_theme_mod('planty_footer_form_placeholder', 'Enter Email Address')); ?>"
						class="form__input" />
				</div>
				<button type="submit"
					class="form__button"><?php echo esc_html(get_theme_mod('planty_footer_form_button', 'Subscribe')); ?></button>
			</form>
			<span class="footer__message" id="planty-footer-note">
				<?php echo esc_html(get_theme_mod('planty_footer_form_note', 'We never spam you!')); ?> </span>
		</div>
		<div class="footer__column">
			<h2 class="footer__label"><?php echo esc_html(get_theme_mod('planty_footer_contact_title', 'Contact')); ?></h2>
			<div class="footer__contacts contacts-footer">
				<?php
				$planty_address_text = get_theme_mod('planty_footer_address_text', '121 King St, Melbourne den 3000, Australia');
				$planty_address_link = get_theme_mod('planty_footer_address_link', '#');
				$planty_email_text   = get_theme_mod('planty_footer_email_text', 'Info@example.com');
				$planty_email_link   = get_theme_mod('planty_footer_email_link', 'mailto:Info@example.com');
				$planty_phone_text   = get_theme_mod('planty_footer_phone_text', '+91 999999999');
				$planty_phone_link   = get_theme_mod('planty_footer_phone_link', 'tel:+91999999999');
				?>
				<div class="contacts-footer__item">
					<div class="contacts-footer__icon">
						<img src="<?php echo esc_url(planty_asset('/img/icons/location.svg')); ?>" alt="" />
					</div>
					<div class="contacts-footer__value">
						<a href="<?php echo esc_url($planty_address_link); ?>"
							class="contacts-footer__link-value"><?php echo esc_html($planty_address_text); ?></a>
					</div>
				</div>
				<div class="contacts-footer__item">
					<div class="contacts-footer__icon">
						<img src="<?php echo esc_url(planty_asset('/img/icons/mail.svg')); ?> " alt="" />
					</div>
					<div class="contacts-footer__value">
						<a href="<?php echo esc_url($planty_email_link); ?>"
							class="contacts-footer__link-value"><?php echo esc_html($planty_email_text); ?></a>
					</div>
				</div>
				<div class="contacts-footer__item">
					<div class="contacts-footer__icon">
						<img src="<?php echo esc_url(planty_asset('/img/icons/phone.svg')); ?> " alt="" />
					</div>
					<div class="contacts-footer__value">
						<a href="<?php echo esc_url($planty_phone_link); ?>"
							class="contacts-footer__link-value"><?php echo esc_html($planty_phone_text); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
</div><!-- .wrapper -->

<?php wp_footer(); ?>

</body>

</html>