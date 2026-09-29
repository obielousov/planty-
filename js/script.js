'use strict'

// Motion is a preference, not a decoration
const plantyReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches

// Autoplay holds while the pointer or the keyboard focus is inside the slider
const plantyHoldAutoplay = (slider) => {
	const swiper = slider.swiper

	if (!swiper || !swiper.autoplay) {
		return
	}

	let hovered = false
	let focused = false

	const update = () => {
		if (hovered || focused) {
			swiper.autoplay.stop()
		} else {
			swiper.autoplay.start()
		}
	}

	slider.addEventListener('mouseenter', () => {
		hovered = true
		update()
	})

	slider.addEventListener('mouseleave', () => {
		hovered = false
		update()
	})

	slider.addEventListener('focusin', () => {
		focused = true
		update()
	})

	slider.addEventListener('focusout', () => {
		focused = false
		update()
	})
}

const plantyScrollToSection = (section) => {
	if (!section) {
		return
	}

	const header = document.querySelector('.header')
	const offset = header ? header.offsetHeight : 0
	const top = section.getBoundingClientRect().top + window.scrollY - offset

	window.scrollTo({top, behavior: plantyReducedMotion ? 'auto' : 'smooth'})
}

// Keeps --header-height in sync with the real header size.
// The header is position: fixed, so the page needs this offset to not slide under it.
document.addEventListener('DOMContentLoaded', () => {
	const header = document.querySelector('.header')

	if (!header) {
		return
	}

	const syncHeaderHeight = () => {
		document.documentElement.style.setProperty('--header-height', `${header.offsetHeight}px`)
	}

	// ResizeObserver fires once on observe and again when webfonts finish loading,
	// so it covers cases a plain window resize listener would miss.
	new ResizeObserver(syncHeaderHeight).observe(header)
})

document.addEventListener('DOMContentLoaded', () => {
	const menu = document.querySelector('.menu')

	if (!menu) {
		return
	}

	const burger = menu.querySelector('.menu__burger')
	const overlay = menu.querySelector('.menu__overlay')

	if (!burger) {
		return
	}

	const isOpen = () => menu.classList.contains('menu--open')

	const openMenu = () => {
		menu.classList.add('menu--open')
		burger.setAttribute('aria-expanded', 'true')
		burger.setAttribute('aria-label', 'Close menu')
		document.body.classList.add('is-locked')

		// The panel covers the page, so the keyboard starts inside it
		const firstLink = menu.querySelector('.menu__link-item')

		if (firstLink) {
			firstLink.focus()
		}
	}

	const closeMenu = () => {
		menu.classList.remove('menu--open')
		burger.setAttribute('aria-expanded', 'false')
		burger.setAttribute('aria-label', 'Open menu')
		document.body.classList.remove('is-locked')

		// Focus would be left on a link inside the hidden panel. On desktop the
		// panel stays open and the button is hidden, so focus has to stay put.
		if (burger.offsetParent !== null) {
			burger.focus()
		}
	}

	burger.addEventListener('click', () => {
		if (isOpen()) {
			closeMenu()
		} else {
			openMenu()
		}
	})

	if (overlay) {
		overlay.addEventListener('click', closeMenu)
	}

	// Escape closes the panel
	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && isOpen()) {
			closeMenu()
		}
	})

	// Following a link inside the panel closes it
	menu.querySelectorAll('.menu__link-item').forEach((link) => {
		link.addEventListener('click', closeMenu)
	})
})

document.addEventListener('DOMContentLoaded', () => {
	const heroSlider = document.querySelector('.hero__slider')

	// Swiper is loaded from a CDN, so it can be missing when that request fails
	if (!heroSlider || typeof Swiper === 'undefined') {
		return
	}

	new Swiper(heroSlider, {
		slidesPerView: 1,
		speed: 1000,
		spaceBetween: 30,

		autoplay: plantyReducedMotion ? false : {delay: 5000},

		a11y: {
			containerRole: 'group',
			containerRoleDescriptionMessage: 'carousel',
			containerMessage: 'Featured plants',
			itemRoleDescriptionMessage: 'slide',
		},

		pagination: {
			// The counter is shared by every slide, so it lives outside the slider
			el: document.querySelector('.hero__pagination'),
			type: 'fraction',
			formatFractionCurrent: (number) => String(number).padStart(2, '0'),
			formatFractionTotal: (number) => String(number).padStart(2, '0'),
			// Swiper puts spaces around the slash by default, the design has none
			renderFraction: (currentClass, totalClass) =>
				`<span class="${currentClass}"></span>/<span class="${totalClass}"></span>`,
		},
	})

	plantyHoldAutoplay(heroSlider)
})

document.addEventListener('DOMContentLoaded', () => {
	const dealSlider = document.querySelector('.deal__slider')

	// Swiper is loaded from a CDN, so it can be missing when that request fails
	if (!dealSlider || typeof Swiper === 'undefined') {
		return
	}

	new Swiper(dealSlider, {
		// Slides per view below the first breakpoint, so this is the phone value
		slidesPerView: 1,
		speed: 1000,
		spaceBetween: 30,

		// Each key is a minimum width in px and applies from that width upwards,
		// so the values have to grow. Placeholders, replace with the design values.
		breakpoints: {
			590: {slidesPerView: 2},
			768: {slidesPerView: 3},
		},

		autoplay: plantyReducedMotion ? false : {delay: 5000},

		a11y: {
			containerRole: 'group',
			containerRoleDescriptionMessage: 'carousel',
			containerMessage: 'Deal of the day',
			itemRoleDescriptionMessage: 'slide',
		},

		pagination: {
			el: dealSlider.querySelector('.deal__pagination'),
			clickable: true,
		},
	})

	plantyHoldAutoplay(dealSlider)
})

document.addEventListener('DOMContentLoaded', () => {
	const plantSlider = document.querySelector('.plant__slider')

	// Swiper is loaded from a CDN, so it can be missing when that request fails
	if (!plantSlider || typeof Swiper === 'undefined') {
		return
	}

	new Swiper(plantSlider, {
		slidesPerView: 1,
		speed: 1000,
		spaceBetween: 30,

		autoplay: plantyReducedMotion ? false : {delay: 5000},

		a11y: {
			containerRole: 'group',
			containerRoleDescriptionMessage: 'carousel',
			containerMessage: 'Plant details',
			itemRoleDescriptionMessage: 'slide',
		},

		pagination: {
			el: plantSlider.querySelector('.plant__pagination'),
			clickable: true,
		},
	})

	plantyHoldAutoplay(plantSlider)
})

document.addEventListener('DOMContentLoaded', () => {
	const reviewsSlider = document.querySelector('.reviews__slider')

	// Swiper is loaded from a CDN, so it can be missing when that request fails
	if (!reviewsSlider || typeof Swiper === 'undefined') {
		return
	}

	new Swiper(reviewsSlider, {
		slidesPerView: 1,
		spaceBetween: 30,

		a11y: {
			containerRole: 'group',
			containerRoleDescriptionMessage: 'carousel',
			containerMessage: 'Customer reviews',
			itemRoleDescriptionMessage: 'slide',
		},

		pagination: {
			el: reviewsSlider.querySelector('.reviews__pagination'),
			clickable: true,
		},

		// The same breakpoints the SCSS uses elsewhere (767.98em and 1023.98em)
		breakpoints: {
			768: {slidesPerView: 2},
			1024: {slidesPerView: 3},
		},
	})
})

// Swiper gives the pagination bullets role="button" and a tabindex, but binds
// only a click, so Enter and Space have to be spelled out
document.addEventListener('keydown', (event) => {
	if (event.key !== 'Enter' && event.key !== ' ') {
		return
	}

	const bullet = event.target.closest('.swiper-pagination-bullet')

	if (!bullet) {
		return
	}

	event.preventDefault()
	bullet.click()
})

document.addEventListener('DOMContentLoaded', () => {
	const counters = document.querySelectorAll('.counter-slide')

	if (!counters.length) {
		return
	}

	// Every slide carries its own deadline in data-deadline, typed by the editor
	// in the local time of the site, so each counter runs down on its own
	const deadlines = new Map()

	counters.forEach((counter) => {
		const slide = counter.closest('[data-deadline]')

		if (!slide || !slide.dataset.deadline) {
			return
		}

		const deadline = new Date(slide.dataset.deadline)

		if (Number.isNaN(deadline.getTime())) {
			return
		}

		deadlines.set(counter, deadline.getTime())
	})

	if (!deadlines.size) {
		return
	}

	const render = () => {
		const now = Date.now()

		// Every tick recomputes the remainder from the current time instead of
		// subtracting a second, so the timer cannot drift away from the clock.
		deadlines.forEach((deadline, counter) => {
			const totalSeconds = Math.max(0, Math.floor((deadline - now) / 1000))

			const values = {
				days: Math.floor(totalSeconds / 86400),
				hours: Math.floor(totalSeconds / 3600) % 24,
				minutes: Math.floor(totalSeconds / 60) % 60,
				seconds: totalSeconds % 60,
			}

			Object.entries(values).forEach(([unit, value]) => {
				const field = counter.querySelector(`[data-counter="${unit}"]`)

				if (field) {
					// Days can run past two digits, the rest are padded to stay even
					field.textContent = String(value).padStart(2, '0')
				}
			})
		})
	}

	render()
	setInterval(render, 1000)
})

// Shop tabs. Buttons carry data-tab and panels carry a matching data-tab-content,
// so the pairing lives in the markup and a WordPress loop can render it as is.
document.addEventListener('DOMContentLoaded', () => {
	const tabs = document.querySelector('.tabs')

	if (!tabs) {
		return
	}

	const navigation = tabs.querySelector('.tabs__navigation')
	const buttons = [...tabs.querySelectorAll('.tabs__title[data-tab]')]

	if (!navigation || !buttons.length) {
		return
	}

	const panelFor = (button) =>
		tabs.querySelector(`.tabs__body[data-tab-content="${button.dataset.tab}"]`)

	const storageKey = 'plantyShopTab'

	const readTab = () => {
		try {
			return window.localStorage.getItem(storageKey)
		} catch (error) {
			return null
		}
	}

	const saveTab = (slug) => {
		try {
			window.localStorage.setItem(storageKey, slug)
		} catch (error) {
			return
		}
	}

	const selectTab = (button, {focus = false, scroll = false} = {}) => {
		buttons.forEach((item) => {
			const isActive = item === button

			item.classList.toggle('tabs__title--active', isActive)
			item.setAttribute('aria-selected', String(isActive))
			// Roving tabindex: Tab reaches the active tab only, arrows move inside
			item.tabIndex = isActive ? 0 : -1

			const panel = panelFor(item)

			if (panel) {
				panel.hidden = !isActive
			}
		})

		saveTab(button.dataset.tab)

		if (scroll) {
			plantyScrollToSection(tabs.closest('.shop'))
		}

		if (focus) {
			button.focus()
		}
	}

	buttons.forEach((button) => {
		button.addEventListener('click', () => selectTab(button, {scroll: true}))
	})

	// Arrow keys walk through the tabs, Home and End jump to the first and the last
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

	// The markup holds the initial state, so the attributes are synced once on load
	const initial = buttons.find((button) => button.classList.contains('tabs__title--active'))

	selectTab(saved || initial || buttons[0])
})

document.addEventListener('DOMContentLoaded', () => {
	const shop = document.querySelector('.shop')

	if (!shop || typeof plantyShop === 'undefined') {
		return
	}

	shop.addEventListener('click', (event) => {
		const link = event.target.closest('.shop__pagination a')

		if (!link) {
			return
		}

		const pagination = link.closest('[data-pagination]')
		const panel = link.closest('.tabs__body')
		const page = parseInt(link.textContent, 10)

		if (!pagination || !panel || !page) {
			return
		}

		event.preventDefault()

		const url = new URL(plantyShop.restUrl, window.location.origin)

		url.searchParams.set('term', pagination.dataset.pagination)
		url.searchParams.set('page', page)

		const status = shop.querySelector('.shop__status')

		panel.setAttribute('aria-busy', 'true')

		fetch(url)
			.then((response) => {
				if (!response.ok) {
					throw new Error(`Request failed with ${response.status}`)
				}

				return response.json()
			})
			.then((data) => {
				panel.innerHTML = data.items + data.pagination

				// The clicked link goes away with the old markup, so focus has to be
				// moved on purpose or the keyboard lands back at the top of the page
				panel.focus({preventScroll: true})

				if (status) {
					status.textContent = `Page ${page} loaded`
				}

				plantyScrollToSection(shop)
			})
			.catch(() => {
				if (status) {
					status.textContent = 'Could not load the page'
				}
			})
			.finally(() => {
				panel.removeAttribute('aria-busy')
			})
	})
})
