# Planty — знання про проєкт

Робочий документ. Тут зібрано все, що ми з'ясували за час роботи: де що лежить, які граблі вже пройдено, що робити далі.

---

## 1. Що це за проєкт

Лендінг квіткового магазину Planty. Спочатку зроблений як статична верстка (HTML + SCSS + трохи JS), тепер переноситься на WordPress як власна тема.

На диску існують дві версії:

| Що                      | Шлях                                             | Стан                    |
| ----------------------- | ------------------------------------------------ | ----------------------- |
| Статична версія         | `B:\front-end\Portfolio\planty`                  | заморожена, недоторкана |
| Сайт (корінь WordPress) | `B:\front-end\OSPanel\home\planty.local`         | робоча версія           |
| Тема                    | `...\home\planty.local\wp-content\themes\planty` | тут уся подальша робота |

**Далі всі правки — тільки в теці теми.** Статична версія більше не редагується, вона лишається як довідник розмітки.

---

## 2. Робочий процес

- Код пише користувач сам. Ніlux складає план: шлях файлу, вміст, що перевірити після.
- Кроки — **по одному**. Не вивалювати п'ять файлів одразу.
- Спершу пояснення простими словами, потім код.
- Не забігати наперед: ACF і кастомні типи записів — лише коли до них дійде черга.

---

## 3. Локальне середовище

- OpenServer Panel **6.5.1** → `B:\front-end\OSPanel`
- Сайт: `http://planty.local`, адмінка: `http://planty.local/wp-admin`
- PHP **8.3**, MySQL **8.4**, база `planty`, користувач `root`, пароль порожній

### Граблі OpenServer 6, які вже пройдено

1. **Тека в `home` не стає проєктом сама.** Потрібна службова тека `.osp` із файлом `project.ini`, де в квадратних дужках — ім'я домену, і воно мусить точно збігатися з іменем теки:

   ```ini
   [planty.local]

   http_engine = Apache
   php_engine = PHP-8.3
   ```

2. **Файли модуля на диску ≠ увімкнений модуль.** У PHP-8.3 і MySQL-8.4 усі файли були на місці, але в `config\<модуль>\module.ini` стояло `enabled = 0`. OpenServer пише оманливе «не встановлено». Лікується галочкою в списку модулів у треї + перезапуск.

3. **Ім'я хоста БД — ім'я модуля, а не `localhost`.** Для цього сайту це `MySQL-8.4`. З `localhost` буде помилка з'єднання.

4. **phpMyAdmin не відкривається за голим `http://phpmyadmin`.** У його конфізі `ServerDefault = 0`, тому без параметра показує лише сторінки налаштувань. Робоча адреса — `http://phpmyadmin/index.php?server=9` (MySQL-8.4 іде дев'ятим у списку серверів).

---

## 4. Що зроблено, що лишилось

**Зроблено:**

- WordPress встановлено, сайт і адмінка працюють
- Домен, база, модулі налаштовані
- У теку теми перенесено активи копіюванням: `css`, `js`, `img` (36 файлів), `fonts`, `scss`
- Структура теки навмисно повторює статичний проєкт, тому відносні шляхи всередині SCSS (`url("../fonts/...")`, `url("../img/...")`) працюють без змін

**Лишилось:**

- У теці теми немає жодного файлу теми — тому в списку тем її поки не видно. Це очікувано
- Натяжка верстки: розрізати `index.html` на шаблони (див. план нижче)
- Прибрати секцію `deal` зі SCSS і `js/script.js`
- Меню через `wp_nav_menu`
- І значно пізніше — ACF і кастомні типи записів

---

## 5. Карта `index.html` (статична версія, 1331 рядок)

| Що                                    | Рядки     |
| ------------------------------------- | --------- |
| Голова документа                      | 1–11      |
| `header`                              | 12–51     |
| `<main>` відкривається                | 52        |
| `hero`                                | 53–74     |
| `deal` — закоментований, прибирається | 75–347    |
| `living`                              | 348–371   |
| `shop` з табами                       | 372–974   |
| `find`                                | 975–983   |
| `plant`                               | 984–1009  |
| `collection`                          | 1010–1042 |
| `reviews`                             | 1043–1209 |
| `</main>`, `footer`                   | 1209–1331 |

Голова містить лише Swiper з CDN і `/css/style.css`. Скрипти в кінці — Swiper з CDN і `js/script.js`.

Розміри SCSS: `_home.scss` 1080 рядків, `_header.scss` 280, `_footer.scss` 258, `mixin.scss` 103, `style.scss` 76, `reset.scss` 68, `_fonts.scss` 24, `variables.scss` 9.

---

## 6. План натяжки на WordPress

### Навіщо взагалі розрізати

WordPress не зберігає готові сторінки — він збирає кожну наново з частин теми. Бо сторінок багато (головна, запис, категорія, 404), і в усіх **верх і низ однакові**, а середина різна. Тому верх і низ виносять в окремі файли.

`index.html` розрізається на: `header.php` (верх), `index.php` (середина), `footer.php` (низ).

### Команди, які зустрінуться в шаблонах

| Команда                        | Простими словами                                           |
| ------------------------------ | ---------------------------------------------------------- |
| `get_header()`                 | встав сюди вміст `header.php`                              |
| `get_footer()`                 | встав сюди вміст `footer.php`                              |
| `wp_head()`                    | WordPress, встав сюди свої службові теги в голову сторінки |
| `wp_footer()`                  | те саме, але перед закриттям сторінки                      |
| `body_class()`                 | WordPress, допиши `<body>` свої класи                      |
| `planty_asset('img/logo.svg')` | дай правильну адресу картинки в темі                       |

### Чому картинки доведеться переназвати

У статичній версії `src="<?php echo esc_url( planty_asset( 'img/logo.svg' ) ); ?>" ` — адреса від кореня сайту, і коренем була тека проєкту. У WordPress корінь сайту — це тека з WordPress, а картинки лежать глибше: `wp-content/themes/planty/img/`. Тому `/img/logo.svg` більше не існує. `planty_asset()` підставляє правильний шлях.

### Файли теми

**`style.css`** — «паспорт» теми, без шапки WordPress тему не побачить. Стилів у ньому немає:

```css
/*
Theme Name:  Planty
Description: Custom theme built from a hand-coded landing page.
Version:     1.0.0
Requires at least: 6.0
Requires PHP: 8.0
License:     GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: planty
*/
```

**`functions.php`** — налаштування теми й підключення стилів:

```php
<?php
/**
 * Theme setup and asset loading.
 */

function planty_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
}
add_action( 'after_setup_theme', 'planty_setup' );

/**
 * URL of a file inside the theme. Replaces the absolute /img/... paths the
 * static layout used, which no longer resolve inside a WordPress install.
 *
 * @param string $path Path relative to the theme root.
 * @return string URL.
 */
function planty_asset( $path ) {
	return get_template_directory_uri() . '/' . ltrim( $path, '/' );
}

function planty_assets() {
	$version = wp_get_theme()->get( 'Version' );

	// Swiper 11, the same build the static layout loaded from the CDN
	wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11' );
	wp_enqueue_style( 'planty', planty_asset( 'css/style.css' ), array( 'swiper' ), $version );

	wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', true );
	wp_enqueue_script( 'planty', planty_asset( 'js/script.js' ), array( 'swiper' ), $version, true );
}
add_action( 'wp_enqueue_scripts', 'planty_assets' );
```

`wp_enqueue_style` / `wp_enqueue_script` замінюють теги `<link>` і `<script>` зі статичної версії.

**`header.php`** — рядки 1–52 з `index.html`, тобто все до `<main class="page">` включно. Зміни:

- `<html lang="en">` → `<html <?php language_attributes(); ?>>`
- `<meta charset="UTF-8" />` → `<meta charset="<?php bloginfo( 'charset' ); ?>" />`
- `<title>` прибрати — його додає `add_theme_support( 'title-tag' )`
- `<link>` і `<script>` прибрати — їх підключає `functions.php`
- перед `</head>` додати `<?php wp_head(); ?>`
- `<body>` → `<body <?php body_class(); ?>>`, одразу після нього `<?php wp_body_open(); ?>`

**`footer.php`** — рядки 1209–1331. Замість тегів `<script>` — `<?php wp_footer(); ?>` перед `</body>`.

**`index.php`** — рядки 53–1208 (секції). Зверху `<?php get_header(); ?>`, знизу `<?php get_footer(); ?>`.

### Заміна шляхів

Увімкнути в редакторі режим регулярних виразів (у VS Code — Alt+R у полі пошуку) і зробити заміну по всьому файлу:

- Знайти: `src="<?php echo esc_url( planty_asset( 'img/([^' ) ); ?>" ]+)"`
- Замінити: `src="<?php echo esc_url( planty_asset( 'img/$1' ) ); ?>"`

Окремо перевірити, чи немає десь `href="/img/` — якщо є, зробити те саме.

### Активація

Зовнішній вигляд → Теми → активувати **Planty** → відкрити `http://planty.local` і порівняти зі статичною версією. Перевірити окремо: шрифти, слайдер відгуків, таби в секції shop, бургер-меню.

---

## 7. Граблі CSS і SCSS, які вже пройдено

### Відсоткова ширина — головна грабля проєкту

Відсоток розв'язується лише проти **визначеного** розміру предка. Якщо ланцюжок десь уривається, CSS відкидає відсоток і поводиться як `auto` — елемент падає на свій натуральний розмір. Симптом: «картинка не розтягується, хоч стоїть `width: 100%`».

Причини, які вже зустрічалися:

- рядковий `<a>` навколо картинки — у рядкового боксу немає власної ширини
- flex-елемент із шириною `auto`, який розмірюється вмістом
- `height: 100%` проти батька, чия висота сама залежить від вмісту (і який до того ж скасовує `aspect-ratio`, бо тоді обидва розміри фіксовані)

### `a { display: inline-block }` у reset

`scss/reset.scss` містить `a { text-decoration: none; display: inline-block; color: inherit }`. Тобто **кожне** посилання в проєкті — inline-block, а inline-block розмірюється вмістом. Якщо всередині `<a>` лежить `<img>` з `width: 100%`, його containing block — той самий `<a>`, ширина якого залежить від картинки → відсоток циклічний → браузер відкидає його як `auto` → картинка падає на натуральний розмір.

**Фікс:** дати `<a>` визначену ширину — `width: 100%` достатньо, але `display: block; width: 100%` краще (inline-block створює рядковий бокс, і під картинкою додається кілька пікселів відступу від baseline).

### Інше

- `img` — replaced-елемент, псевдоелементи на ньому **не генеруються взагалі**. Тому шар затемнення треба вішати на обгортку.
- У `_fonts.scss` лише 400/500/600 — Montserrat Bold немає, усі `font-weight: 700` рендеряться синтетичним жирним.
- Хедер непрозорий на всіх ширинах: біла основа + зелений градієнтний шар на `.header__container`. Симптом «хедер просвічується» був не про z-index (`.header` має 100, `.swiper` — 1), а про альфу `--light-green-color` = rgba(...,0.2).

---

## 8. Граблі Swiper 11

- Кожен слайдер — **окремий блок `DOMContentLoaded`** зі своїм `new Swiper()`. Спільної ініціалізації немає навмисно.
- Слайдер мусить мати **визначену ширину до ініціалізації**, інакше Swiper міряє контейнер, розмір якого залежить від слайдів, а слайди — від картинок. У flex-рядку це `flex: 1; min-inline-size: 0`.
- Swiper пише ширину слайдів інлайновим стилем за формулою `(ширина слайдера − проміжки) / slidesPerView`. Тому при числовому `slidesPerView` слайд **завжди** дорівнює частці контейнера — фіксованих 297px так не отримати. Для фіксованої ширини з підрізаним сусідом потрібно `slidesPerView: 'auto'` + `inline-size` на слайді в CSS.
- `slidesPerView: '3'` як рядок працює (Swiper проганяє через `parseFloat`), але API чекає `number | 'auto'` — писати числом.
- **Пагінація зникає, якщо слайдер розтягнутий по висоті.** `.swiper-wrapper` має `height: 100%`, тож заповнює всю висоту слайдера, а пагінація в потоці опиняється за його межею і її зрізає `overflow: hidden`. Лікується `align-self: flex-start` на слайдері.
- Булети розставляє правило на **три класи**: `.swiper-horizontal > .swiper-pagination-bullets .swiper-pagination-bullet { margin: 0 var(--swiper-pagination-bullet-horizontal-gap, 4px) }`. Будь-яке `margin: 0` у дворівневому селекторі йому програє. Правильний важіль — обнулити змінну на контейнері пагінації.
- `position: static` на пагінації працює лише тому, що `style.css` підключений **після** CDN-стилів (однакова специфічність, вирішує порядок).
- У CSS Swiper є `.swiper-pagination-bullet:only-child { display: none !important }` — коли сторінка лишається одна, єдиний булет ховається. Це не поломка, так задумано.

---

## 9. Незмінні правила проєкту

- Партіали SCSS **плоскі, без підпапок**.
- Кожен партіал, що використовує `toRem()`/`toEm()`, мусить мати **власний** `@use "mixin" as *` — модулі Sass ізольовані.
- Порожні BEM-плейсхолдери в SCSS — це стиль проєкту, не сміття.
- Коментарі в коді — **англійською**.
- Збірка SCSS зовнішня, `package.json` немає. Після правок у SCSS треба перезібрати `css/style.css`, інакше в браузері буде стара версія.
