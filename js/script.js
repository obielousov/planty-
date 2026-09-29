// Planty theme scripts. Wrapped in an IIFE, so nothing leaks to the global scope.
;(() => {
	'use strict'

	// Selectors
	const SELECTORS = {
		reveal: '[data-reveal]',
		header: '.header',
		menu: '.menu',
		menuBurger: '.menu__burger',
		menuOverlay: '.menu__overlay',
		menuLink: '.menu__link-item',
		heroSlider: '.hero__slider',
		heroPagination: '.hero__pagination',
		dealSlider: '.deal__slider',
		dealPagination: '.deal__pagination',
		plantSlider: '.plant__slider',
		plantPagination: '.plant__pagination',
		reviewsSlider: '.reviews__slider',
		reviewsPagination: '.reviews__pagination',
		bullet: '.swiper-pagination-bullet',
		counterSlide: '.counter-slide',
		counterDeadline: '[data-deadline]',
		counterField: (unit) => `[data-counter="${unit}"]`,
		tabs: '.tabs',
		tabsNavigation: '.tabs__navigation',
		tabsButton: '.tabs__title[data-tab]',
		tabsPanel: '.tabs__body',
		tabsPanelFor: (slug) => `.tabs__body[data-tab-content="${slug}"]`,
		shop: '.shop',
		shopPagination: '[data-pagination]',
		shopPaginationLink: '.shop__pagination a',
		shopStatus: '.shop__status',
	}

	// Classes toggled from JS
	const CLASSES = {
		menuOpen: 'menu--open',
		bodyLocked: 'is-locked',
		tabActive: 'tabs__title--active',
		revealed: 'is-visible',
		loaded: 'loaded',
	}

	// Keys that activate a focused control
	const ACTIVATION_KEYS = ['Enter', ' ']
	const TAB_KEY = 'plantyShopTab'

	// Helpers

	// Respect the system motion preference
	const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
	const autoplayDelay = reducedMotion ? false : {delay: 5000}

	const padTwo = (number) => String(number).padStart(2, '0')

	// Runs once the document is ready, even if that already happened
	const ready = (callback) =>
		document.readyState === 'loading'
			? document.addEventListener('DOMContentLoaded', callback, {once: true})
			: callback()

	// Scrolls a section into view, leaving room for the fixed header
	const scrollToSection = (section) => {
		if (!section) {
			return
		}

		const header = document.querySelector(SELECTORS.header)
		const offset = header ? header.offsetHeight : 0

		window.scrollTo({
			top: section.getBoundingClientRect().top + window.scrollY - offset,
			behavior: reducedMotion ? 'auto' : 'smooth',
		})
	}

	// Pauses autoplay while the pointer or the keyboard is inside the slider
	const holdAutoplay = (slider) => {
		const autoplay = slider.swiper && slider.swiper.autoplay

		if (!autoplay) {
			return
		}

		const state = {hovered: false, focused: false}

		const update = () => {
			if (state.hovered || state.focused) {
				autoplay.stop()
			} else {
				autoplay.start()
			}
		}

		const track = (event, key, value) => {
			slider.addEventListener(event, () => {
				state[key] = value
				update()
			})
		}

		track('mouseenter', 'hovered', true)
		track('mouseleave', 'hovered', false)
		track('focusin', 'focused', true)
		track('focusout', 'focused', false)
	}

	// Shared Swiper options, so no config repeats them
	const createSlider = ({
		selector,
		paginationSelector,
		message,
		autoplay = false,
		pagination,
		...options
	}) => {
		const element = document.querySelector(selector)

		// Swiper is loaded from a CDN, it may be missing
		if (!element || typeof Swiper === 'undefined') {
			return
		}

		new Swiper(element, {
			slidesPerView: 1,
			speed: 1000,
			spaceBetween: 30,
			...options,
			autoplay: autoplay ? autoplayDelay : false,
			a11y: {
				containerRole: 'group',
				containerRoleDescriptionMessage: 'carousel',
				containerMessage: message,
				itemRoleDescriptionMessage: 'slide',
			},
			pagination: {
				...pagination,
				// Hero counter is outside the slider, the rest are inside
				el:
					element.querySelector(paginationSelector) ||
					document.querySelector(paginationSelector),
			},
		})

		holdAutoplay(element)
	}

	// Sliders

	// Hero
	const initHeroSlider = () =>
		createSlider({
			selector: SELECTORS.heroSlider,
			paginationSelector: SELECTORS.heroPagination,
			message: 'Featured plants',
			autoplay: true,
			pagination: {
				type: 'fraction',
				formatFractionCurrent: padTwo,
				formatFractionTotal: padTwo,
				// Swiper adds spaces around the slash, the design has none
				renderFraction: (current, total) =>
					`<span class="${current}"></span>/<span class="${total}"></span>`,
			},
		})

	// Deal
	const initDealSlider = () =>
		createSlider({
			selector: SELECTORS.dealSlider,
			paginationSelector: SELECTORS.dealPagination,
			message: 'Deal of the day',
			autoplay: true,
			// Keys are minimum widths in px, applied upwards
			breakpoints: {590: {slidesPerView: 2}, 768: {slidesPerView: 3}},
			pagination: {clickable: true},
		})

	// Plant
	const initPlantSlider = () =>
		createSlider({
			selector: SELECTORS.plantSlider,
			paginationSelector: SELECTORS.plantPagination,
			message: 'Plant details',
			autoplay: true,
			pagination: {clickable: true},
		})

	// Reviews, moved by the reader, so no autoplay
	const initReviewsSlider = () =>
		createSlider({
			selector: SELECTORS.reviewsSlider,
			paginationSelector: SELECTORS.reviewsPagination,
			message: 'Customer reviews',
			// Swiper default
			speed: 300,
			// Same breakpoints as in the SCSS
			breakpoints: {768: {slidesPerView: 2}, 1024: {slidesPerView: 3}},
			pagination: {clickable: true},
		})

	// Page features

	// Releases the hero entrance once the page has settled
	const initLoaded = () => {
		const mark = () => document.documentElement.classList.add(CLASSES.loaded)

		if (document.readyState === 'complete') {
			mark()
		} else {
			window.addEventListener('load', mark, {once: true})
			// Never leave the hero hidden because a single image is slow
			window.setTimeout(mark, 1500)
		}
	}

	// Reveals an element the first time it scrolls into view
	const initReveal = () => {
		const elements = document.querySelectorAll(SELECTORS.reveal)

		// Without an observer, or when motion is unwanted, everything stays visible
		if (!elements.length || reducedMotion || !('IntersectionObserver' in window)) {
			return
		}

		const observer = new IntersectionObserver(
			(entries) => {
				entries.forEach((entry) => {
					if (!entry.isIntersecting) {
						return
					}

					entry.target.classList.add(CLASSES.revealed)
					// Once shown it stays shown, so there is nothing left to watch
					observer.unobserve(entry.target)
				})
			},
			// Bottom 10% of the viewport is ignored, so it fires a little early
			{rootMargin: '0px 0px -10% 0px'}
		)

		elements.forEach((element) => observer.observe(element))
	}

	// Burger menu. Focus moves into the panel and back to the burger
	const initMenu = () => {
		const menu = document.querySelector(SELECTORS.menu)
		const burger = menu?.querySelector(SELECTORS.menuBurger)

		if (!menu || !burger) {
			return
		}

		const overlay = menu.querySelector(SELECTORS.menuOverlay)
		const links = menu.querySelectorAll(SELECTORS.menuLink)
		const isOpen = () => menu.classList.contains(CLASSES.menuOpen)

		const toggle = (open) => {
			menu.classList.toggle(CLASSES.menuOpen, open)
			document.body.classList.toggle(CLASSES.bodyLocked, open)
			burger.setAttribute('aria-expanded', String(open))
			burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu')

			// On desktop the burger is hidden, so leave focus where it is
			if (open) {
				links[0]?.focus()
			} else if (burger.offsetParent !== null) {
				burger.focus()
			}
		}

		burger.addEventListener('click', () => toggle(!isOpen()))
		overlay?.addEventListener('click', () => toggle(false))
		links.forEach((link) => link.addEventListener('click', () => toggle(false)))

		// Escape closes
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && isOpen()) {
				toggle(false)
			}
		})
	}

	// Swiper binds click on bullets only, so Enter and Space are added here
	const initBulletKeyboard = () => {
		document.addEventListener('keydown', (event) => {
			if (!ACTIVATION_KEYS.includes(event.key)) {
				return
			}

			const bullet = event.target.closest(SELECTORS.bullet)

			if (!bullet) {
				return
			}

			event.preventDefault()
			bullet.click()
		})
	}

	// Each slide carries its own deadline in data-deadline
	const initCounters = () => {
		const deadlines = new Map()

		document.querySelectorAll(SELECTORS.counterSlide).forEach((counter) => {
			const value = counter.closest(SELECTORS.counterDeadline)?.dataset.deadline
			const deadline = value ? new Date(value) : null

			if (deadline && !Number.isNaN(deadline.getTime())) {
				deadlines.set(counter, deadline.getTime())
			}
		})

		if (!deadlines.size) {
			return
		}

		const render = () => {
			const now = Date.now()

			// Recomputed from the clock on every tick, so it cannot drift
			deadlines.forEach((deadline, counter) => {
				const total = Math.max(0, Math.floor((deadline - now) / 1000))

				const values = {
					days: Math.floor(total / 86400),
					hours: Math.floor(total / 3600) % 24,
					minutes: Math.floor(total / 60) % 60,
					seconds: total % 60,
				}

				Object.entries(values).forEach(([unit, value]) => {
					const field = counter.querySelector(SELECTORS.counterField(unit))

					// Days can pass two digits, the rest stay padded
					if (field) {
						field.textContent = padTwo(value)
					}
				})
			})
		}

		render()
		setInterval(render, 1000)
	}

	// Tabs: data-tab on the button, data-tab-content on the panel
	const initTabs = () => {
		const tabs = document.querySelector(SELECTORS.tabs)
		const navigation = tabs?.querySelector(SELECTORS.tabsNavigation)
		const buttons = tabs ? [...tabs.querySelectorAll(SELECTORS.tabsButton)] : []

		if (!navigation || !buttons.length) {
			return
		}

		const readTab = () => {
			try {
				return window.localStorage.getItem(TAB_KEY)
			} catch (error) {
				return null
			}
		}

		const selectTab = (button, {focus = false, scroll = false} = {}) => {
			buttons.forEach((item) => {
				const isActive = item === button

				item.classList.toggle(CLASSES.tabActive, isActive)
				item.setAttribute('aria-selected', String(isActive))
				// Roving tabindex, arrows move between the tabs
				item.tabIndex = isActive ? 0 : -1

				const panel = tabs.querySelector(SELECTORS.tabsPanelFor(item.dataset.tab))

				if (panel) {
					panel.hidden = !isActive
				}
			})

			try {
				window.localStorage.setItem(TAB_KEY, button.dataset.tab)
			} catch (error) {
				// Storage can be blocked, the tabs still work
			}

			if (scroll) {
				scrollToSection(tabs.closest(SELECTORS.shop))
			}

			if (focus) {
				button.focus()
			}
		}

		buttons.forEach((button) => {
			button.addEventListener('click', () => selectTab(button, {scroll: true}))
		})

		// Arrows walk the tabs, Home and End jump
		navigation.addEventListener('keydown', (event) => {
			const current = buttons.indexOf(document.activeElement)
			const last = buttons.length - 1

			const target = {
				ArrowRight: current === last ? 0 : current + 1,
				ArrowLeft: current === 0 ? last : current - 1,
				Home: 0,
				End: last,
			}[event.key]

			if (current === -1 || target === undefined) {
				return
			}

			event.preventDefault()
			selectTab(buttons[target], {focus: true})
		})

		const saved = buttons.find((button) => button.dataset.tab === readTab())
		// Sync the attributes with the state the markup already has
		const initial = buttons.find((button) => button.classList.contains(CLASSES.tabActive))

		selectTab(saved || initial || buttons[0])
	}

	// Shop pagination, further pages come from the REST route
	const initShopPagination = () => {
		const shop = document.querySelector(SELECTORS.shop)

		// plantyShop comes from wp_localize_script
		if (!shop || typeof plantyShop === 'undefined') {
			return
		}

		shop.addEventListener('click', async (event) => {
			const link = event.target.closest(SELECTORS.shopPaginationLink)

			if (!link) {
				return
			}

			const pagination = link.closest(SELECTORS.shopPagination)
			const panel = link.closest(SELECTORS.tabsPanel)
			const page = parseInt(link.textContent, 10)

			if (!pagination || !panel || !page) {
				return
			}

			event.preventDefault()

			const url = new URL(plantyShop.restUrl, window.location.origin)

			url.searchParams.set('term', pagination.dataset.pagination)
			url.searchParams.set('page', page)

			const status = shop.querySelector(SELECTORS.shopStatus)

			panel.setAttribute('aria-busy', 'true')

			try {
				const response = await fetch(url)

				if (!response.ok) {
					throw new Error(`Request failed with ${response.status}`)
				}

				const data = await response.json()

				panel.innerHTML = data.items + data.pagination

				// The clicked link is gone with the old markup, so move focus
				panel.focus({preventScroll: true})

				if (status) {
					status.textContent = `Page ${page} loaded`
				}

				scrollToSection(shop)
			} catch (error) {
				if (status) {
					status.textContent = 'Could not load the page'
				}
			} finally {
				panel.removeAttribute('aria-busy')
			}
		})
	}

	// Bootstrap

	// Every feature starts here
	ready(() => {
		initLoaded()
		initReveal()
		initMenu()
		initHeroSlider()
		initDealSlider()
		initPlantSlider()
		initReviewsSlider()
		initBulletKeyboard()
		initCounters()
		initTabs()
		initShopPagination()
	})
})()
