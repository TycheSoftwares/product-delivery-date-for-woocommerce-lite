# Product Delivery Date for WooCommerce - Lite

Lets customers choose a delivery date for each product on the WooCommerce product page. For each product, the store owner picks the weekdays deliveries are made on, the minimum delivery time, how far ahead a date can be chosen, the holidays, and whether the date is mandatory. Global holidays and the calendar look are set on the Settings screens.

This is the free version on WordPress.org. The Pro version (`product-delivery-date`, text domain `woocommerce-prdd`) adds time slots, delivery charges, lockouts, Google Calendar sync and more. Those Pro screens appear in Lite as locked previews.

| | |
| --- | --- |
| Version | 4.0.0 |
| Text domain | `product-delivery-date-for-woocommerce-lite` |
| Requires WordPress | 6.2+ (tested up to 7.1) |
| Requires PHP | 7.4+ |
| Requires WooCommerce | 5.0.0+ (tested up to 11.2.1), declared through `Requires Plugins: woocommerce` |
| HPOS | Compatible (`custom_order_tables` is declared in `before_woocommerce_init`) |
| License | GPLv2 or later |
| Author | [Tyche Softwares](https://www.tychesoftwares.com/) |

This file is for developers working on the plugin. The readme for customers and WordPress.org is [`readme.txt`](readme.txt), and the release history is in [`changelog.txt`](changelog.txt).

---

## Contents

- [Features](#features)
- [Requirements for development](#requirements-for-development)
- [Getting started](#getting-started)
- [Project structure](#project-structure)
- [Architecture](#architecture)
- [How a delivery works](#how-a-delivery-works)
- [Admin screens and settings](#admin-screens-and-settings)
- [Data storage](#data-storage)
- [Lite and Pro together](#lite-and-pro-together)
- [npm scripts](#npm-scripts)
- [Translations](#translations)
- [Coding standards and QA](#coding-standards-and-qa)
- [REST API](#rest-api)
- [Extending the plugin](#extending-the-plugin)
- [Release checklist](#release-checklist)
- [Troubleshooting](#troubleshooting)

---

## Features

- A delivery date calendar (jQuery UI datepicker) on the product page, above the Add to Cart button.
- Per-product settings:
  - enable the delivery date;
  - delivery weekdays;
  - minimum delivery time (in hours);
  - number of dates available to choose;
  - product holidays;
  - a mandatory delivery date field.
- Global holidays, which are blocked on every product.
- Calendar appearance: language, date format, number of months shown, first day of the week, and one of 24 jQuery UI themes.
- The delivery date is shown in the cart, at checkout (classic and block checkout), on the order, and in the emails.
- **View Deliveries:** a list and a calendar view of the orders that have a delivery date.
- Personal data export of the delivery dates (Tools → Export Personal Data).
- One-click migration of the Lite settings and order dates to the Pro format.
- Locked previews of the Pro screens, with an Upgrade to Pro modal.

---

## Requirements for development

- PHP 7.4+, WordPress 6.2+ and WooCommerce 5.0.0+ on a local site (this repo is developed on [Local](https://localwp.com/)).
- Node.js and npm, for [`@wordpress/scripts`](https://www.npmjs.com/package/@wordpress/scripts). There is no `.nvmrc`. Use a current LTS version.
- [WP-CLI](https://wp-cli.org/), for `npm run i18n:pot` and for Plugin Check.
- PHP_CodeSniffer with the WordPress Coding Standards, for [`tyche-phpcs.xml`](tyche-phpcs.xml).

The `Dangerfile` and `Gemfile` are used by the CI to review pull requests.

---

## Getting started

```bash
cd wp-content/plugins/product-delivery-date-for-woocommerce-lite
npm install
npm run build     # production build into build/
npm start         # or: rebuild on every change while developing
```

Then activate **WooCommerce** and **Product Delivery Date for WooCommerce - Lite**. The settings are under **Product Delivery Date** in the admin menu. The product settings are in the **Product Delivery Date** meta box on the product edit screen.

`build/` is committed and shipped. The plugin loads `build/*.js`, not `src/`, so run `npm run build` before you commit a change to `src/`.

---

## Project structure

```
product-delivery-date-for-woocommerce-lite/
├── product-delivery-date-for-woocommerce-lite.php   Plugin header, constants, HPOS declaration
├── class-prdd-lite-woocommerce.php                  Main class: hooks, scripts, install, migration to Pro
├── uninstall.php                                    Removes the data, only when the setting allows it
├── readme.txt / changelog.txt                       WordPress.org readme and changelog
├── package.json                                     wp-scripts build, lint and i18n scripts
├── tyche-phpcs.xml                                  PHPCS ruleset
├── src/                                             React source (not shipped to the browser)
│   ├── admin-settings/                              Settings app (Product Delivery Date menu)
│   │   ├── App.js                                   Routes
│   │   ├── legacy.js                                Maps the earlier ?action= URLs to the routes
│   │   ├── components/  hooks/
│   │   └── screens/                                 home, settings, appearance, integrations, bulk, deliveries
│   ├── product-metabox/                             Product meta box app
│   │   ├── DeliverySettingsForm.js                  Tabs and fields
│   │   └── tabs/                                    Delivery Options (live) + locked Pro tab previews
│   └── shared/                                      api.js, config.js and shared components
├── build/                                           Compiled bundles + *.asset.php (committed)
├── includes/
│   ├── class-prdd-lite-process.php                  Product page field, cart and order item meta
│   ├── class-prdd-lite-validation.php               Add to cart validation
│   ├── class-prdd-lite-common.php                   Helpers
│   ├── class-prdd-privacy-policy-lite.php           Personal data export
│   ├── prdd-lite-config.php                         Languages, date formats, themes, weekdays
│   ├── prdd-lite-component.php                      Loads the Tyche components below
│   ├── admin/
│   │   ├── class-prdd-lite-global-menu.php          Admin menu
│   │   ├── class-prdd-lite-admin-react-page.php     Hosts the settings app, window.prddLiteAdmin
│   │   ├── class-prdd-lite-settings-service.php     Reads and saves the global settings
│   │   ├── class-prdd-lite-product-metabox-react.php  Product meta box app, window.prddLiteProductMetabox
│   │   ├── class-prdd-lite-meta-box.php             Saves and duplicates the product settings
│   │   ├── class-prdd-lite-view-deliveries-table.php  WP_List_Table of View Deliveries
│   │   ├── class-prdd-lite-calendar-view.php        Events JSON of the deliveries calendar
│   │   └── api/                                     REST controllers (prdd-lite/admin/v1)
│   └── component/                                   Tyche shared components
│       ├── woocommerce-check/                       Notice when WooCommerce is inactive
│       ├── faq-support/                             FAQ content
│       ├── upgrade-to-pro/                          Upgrade to Pro notices
│       ├── pro-notices-in-lite/                     (not instantiated)
│       ├── plugin-tracking/                         Opt-in usage tracking
│       └── plugin-deactivation/                     Deactivation survey
├── assets/                                          Admin CSS, fonts, images, choices.js
├── css/  images/                                    Front end datepicker CSS and the 24 jQuery UI themes
├── js/                                              Front end and legacy admin scripts, fullcalendar, i18n
└── languages/                                       product-delivery-date-for-woocommerce-lite.pot
```

---

## Architecture

### Admin screens (React)

There are two React apps, built by `wp-scripts` from two entries:

| Bundle | Entry | Loaded on | Config |
| --- | --- | --- | --- |
| `build/admin-settings.js` | `src/admin-settings/index.js` | Product Delivery Date menu (`woocommerce_prdd_lite_page`, `woocommerce_prdd_lite_history_page`) | `window.prddLiteAdmin`, from `PRDD_Lite_Admin_React_Page::get_config()` |
| `build/product-metabox.js` | `src/product-metabox/index.js` | Product edit screen | `window.prddLiteProductMetabox`, from `PRDD_Lite_Product_Metabox_React` |

- The dependencies and the version of each bundle come from its `build/*.asset.php`.
- The config is printed with `wp_add_inline_script( …, 'before' )`.
- The translations are set with `wp_set_script_translations()`.
- `src/shared/config.js` reads whichever of the two config objects is present.

The settings app uses a `HashRouter` (react-router-dom 6). These are the routes:

| Route | Screen | Status |
| --- | --- | --- |
| `/` | Welcome | Live |
| `/faq` | FAQ | Live |
| `/settings` | Settings | Live |
| `/settings/block-time-slot` | Block Time Slot | Locked Pro preview |
| `/settings/holidays` | Holidays | Live |
| `/appearance` | Calendar Appearance | Live |
| `/appearance/field-labels` (also `field-label`) | Field Labels | Locked Pro preview |
| `/integrations` | Google Calendar Sync | Locked Pro preview |
| `/integrations/subscriptions` | Subscriptions | Locked Pro preview |
| `/bulk-product-settings` | Bulk Product Settings | Locked Pro preview |
| `/view-deliveries` | View Deliveries (list and calendar) | Live |

Any other route redirects to `/`. `legacy.js` maps the URLs used before 4.0.0 to these routes, so old bookmarks and links keep working. One example is `?page=woocommerce_prdd_lite_page&action=holidays`. The `View Deliveries` and `Settings` submenus point to the app routes through the `parent_file` filter (`PRDD_Lite_Global_Menu::prdd_lite_submenu_app_links`).

### Locked Pro previews

- **What they are:**
  - The Pro-only screens (above) and the Pro tabs of the product meta box are **static markup** (`src/shared/components/Preview.js`, `LockedScreen.js`, `UpgradeModal.js`).
  - The Pro tabs are Manage Availability, Delivery Time Period, Delivery Charges and Settings.
- **What they show:** each preview has the same classes as the Pro screen, so the Pro CSS draws it, but it has no state, no API calls and no Pro code.
- **How they work:** `LockedScreen` places the preview under the Upgrade to Pro modal.
- **Rules:**
  - Keep them static. Don't import Pro logic into Lite.
  - When the Pro screen changes, update its preview markup.

### Front end

The front end is PHP and jQuery, not React:

- `Prdd_Lite_Process::prdd_lite_after_add_to_cart()` prints the field.
- `js/initialize-datepicker.js` sets up the jQuery UI datepicker from the localized product and global settings (`prdd_localize_global_settings()`).
- The datepicker language file is loaded from `js/i18n/`, and the theme CSS from `css/themes/`.

### Tracking and other components

`includes/prdd-lite-component.php` loads the Tyche components:

- the WooCommerce check;
- FAQ and support;
- Upgrade to Pro;
- opt-in plugin tracking (`Tyche_Plugin_Tracking`, `ts_tracker_*` filters);
- the deactivation survey.

Tracking only sends data after the admin opts in.

---

## How a delivery works

1. **Product page:**
   - On `woocommerce_before_add_to_cart_button`, the delivery date field is printed for products that have `_woo_prdd_lite_enable_delivery_date = on`.
   - The datepicker disables these dates:
     - weekdays that are not selected;
     - the dates within the minimum delivery time;
     - the dates after the maximum number of days;
     - the product holidays;
     - the global holidays (plus `prdd_lite_additional_holidays`).
2. **Add to cart:**
   - `Prdd_Lite_Validation::prdd_lite_get_validate_add_cart_item()` (`woocommerce_add_to_cart_validation`) blocks the add to cart when the date is mandatory and empty.
   - `prdd_lite_add_cart_item_data()` (`woocommerce_add_cart_item_data`) stores the date in `$cart_item['prdd_lite_delivery'][0]`. It reads two posted values:
     - `delivery_calender_lite`, the date shown to the customer;
     - `prdd_lite_hidden_date`, the date in `j-n-Y` form.
3. **Cart session:** `prdd_lite_get_cart_item_from_session()` restores the date. `prdd_lite_get_item_data()` (`woocommerce_get_item_data`) shows it in the cart and on the checkout.
4. **Order:**
   - `prdd_lite_add_order_item_meta()` runs on `woocommerce_checkout_create_order_line_item`. It saves `Delivery Date`, `_prdd_lite_date` (`Y-m-d`) and `_prdd_date` (`Y-m-d`) on that line item.
   - This one hook covers both checkouts. The classic checkout and the block checkout (Store API) both create the line items through `WC()->checkout->create_order_line_items()`, so each item gets its own date, variations included.
   - `woocommerce_hidden_order_itemmeta` hides the `_` keys in the order screen.
5. **Admin:** the order appears in **View Deliveries**, both in the list and in the calendar.

---

## Admin screens and settings

The menu is **Product Delivery Date** (capability `manage_woocommerce`), with the submenus **View Deliveries** and **Settings**.

### Settings → Settings

| Field | Option |
| --- | --- |
| Enable rounding of prices | `prdd_lite_enable_rounding` |
| Keep data when the plugin is deleted | `prdd_lite_enable_delete_order_item`. This option is the inverse: `on` means **delete** the data. |

### Settings → Holidays

Global holidays, saved as a comma separated list of `j-n-Y` dates in `prdd_lite_global_holidays`.

### Appearance → Calendar Appearance

| Field | Option | Default |
| --- | --- | --- |
| Calendar language | `prdd_lite_language` | `en-GB` |
| Date format | `prdd_lite_date_format` | `mm/dd/y` |
| Number of months to show | `prdd_lite_months` | `1` |
| First day of the week | `prdd_lite_calendar_day` | `1` |
| Calendar theme | `prdd_lite_theme` | `smoothness` |

The choices for the language, date format and theme come from `prdd-lite-config.php`.

The settings are also copied into the Pro keys in `woocommerce_prdd_global_settings`. See [Lite and Pro together](#lite-and-pro-together).

### View Deliveries

- **List view:** `PRDD_Lite_View_Deliveries_Table`, a `WP_List_Table`. It is rendered on the server and returned by the `/view-deliveries` REST route.
- **Calendar view:** FullCalendar (`js/fullcalendar/`), fed by the `prdd-adminend-events-jsons` events URL (`Prdd_Lite_Calendar_View`).
- The old `woocommerce_prdd_lite_history_page` slug still works and redirects to `#/view-deliveries`.

### Product meta box (Product Delivery Date)

- **Delivery Options tab:** the live tab. It has the enable switch, weekdays, minimum delivery time, maximum number of days, holidays, and the mandatory field.
- **How it saves:**
  - The app keeps its state in a hidden JSON field.
  - `PRDD_Lite_Product_Metabox_React::process_product_meta()` (`woocommerce_process_product_meta`, priority 1) decodes it.
  - `Prdd_Lite_Meta_Box_Class` then saves it.
- **Product duplicate:** when a product is duplicated (`woocommerce_duplicate_product`), its settings are copied.

---

## Data storage

Keep these keys as they are. Pro reads the Lite keys during the migration, and existing stores depend on them.

| Where | Key | Content |
| --- | --- | --- |
| Product meta | `_woo_prdd_lite_enable_delivery_date` | `on` or empty |
| Product meta | `_woo_prdd_lite_delivery_days` | Array of selected weekdays |
| Product meta | `_woo_prdd_lite_minimum_delivery_time` | Hours |
| Product meta | `_woo_prdd_lite_maximum_number_days` | Number of dates to show (default 30) |
| Product meta | `_woo_prdd_lite_holidays` | Product holidays |
| Product meta | `_woo_prdd_lite_delivery_field_mandatory` | `on` or empty |
| Product meta | `woocommerce_prdd_settings` | Pro format settings, written by the migration |
| Order item meta | `Delivery Date` | Date shown to the customer |
| Order item meta | `_prdd_lite_date` | `Y-m-d` |
| Order item meta | `_prdd_date` | `Y-m-d`, the Pro key |
| Option | `prdd_lite_language`, `prdd_lite_date_format`, `prdd_lite_months`, `prdd_lite_calendar_day`, `prdd_lite_theme` | Calendar appearance |
| Option | `prdd_lite_global_holidays` | Global holidays |
| Option | `prdd_lite_enable_rounding` | Price rounding |
| Option | `prdd_lite_enable_delete_order_item` | `on` = delete the data on uninstall |
| Option | `woocommerce_prdd_global_settings` | JSON copy of the global settings in the Pro keys |
| Option | `woocommerce_prdd_lite_db_version` | Installed version |
| Option | `prdd_is_data_migrated` | `''`, `yes` (notice shown) or `done` |
| Option | `prdd_lite_allow_tracking`, `ts_tracker_last_send` | Tracking |

### Uninstall

- `uninstall.php` deletes data only when `prdd_lite_enable_delete_order_item` is `on`. That means the admin has turned off "Keep data when the plugin is deleted".
- When it runs, it removes:
  - the product meta above;
  - the `_prdd_lite_date` order item meta;
  - the plugin options.
- On multisite, it runs for every site.
- It does not remove `_prdd_date` or `Delivery Date`, so the Pro version and the orders keep the dates.

---

## Lite and Pro together

- **Different names, so both can be active:**
  - text domain;
  - REST namespace (`prdd-lite/admin/v1` and `prdd/admin/v1`);
  - JS config (`window.prddLiteAdmin` and `window.prddAdmin`);
  - JS hooks (`prdd-lite.*` and `prdd.*`);
  - menu slugs.
- **Shared keys:**
  - Lite writes `_prdd_date` on order items next to `_prdd_lite_date`.
  - It also copies the global settings into `woocommerce_prdd_global_settings`, keeping any keys saved by Pro.
- **Migration to Pro:**
  - When a product has the delivery date enabled and `prdd_is_data_migrated` is not `done`, `prdd_lite_update_db_check()` shows a migration notice.
  - The **Update** button calls the `prdd_lite_update_database` AJAX action (`js/prdd-lite-update-script.js`). The action needs `manage_woocommerce` and the `ajax-nonce` nonce.
  - The action works in pages of `PRDD_LITE_MAX_PRODUCTS_FOR_MIGRATION` (10) products. For each product, it:
    - writes `woocommerce_prdd_settings`;
    - adds `_prdd_date` to that product's existing order items, on both HPOS and posts storage.
  - It then copies the global options into `woocommerce_prdd_global_settings` and sets `prdd_is_data_migrated` to `done`.
- **Pro installed:** the `pro_installed` config flag, from the `prdd_pro_installed` option, changes the Upgrade to Pro links.

---

## npm scripts

| Script | What it does |
| --- | --- |
| `npm run build` | Production build of `admin-settings` and `product-metabox` into `build/` |
| `npm start` | Same entries, watch mode |
| `npm run format` | `wp-scripts format` |
| `npm run lint:js` | `wp-scripts lint-js` |
| `npm run i18n:pot` | `wp i18n make-pot` into `languages/product-delivery-date-for-woocommerce-lite.pot` (excludes `node_modules` and `vendor`, reads `src/` and `build/`) |

---

## Translations

- **Text domain and loading:**
  - The text domain is `product-delivery-date-for-woocommerce-lite`, with Domain Path `/languages/`.
  - On WordPress.org, translate.wordpress.org provides the language packs.
  - `prdd_lite_update_po_file()` runs on `init` and loads every one of these files that exists, for the user's locale (`determine_locale()`):
    1. `WP_LANG_DIR/product-delivery-date-for-woocommerce-lite-{locale}.mo`
    2. `WP_LANG_DIR/woocommerce-prdd-lite-{locale}.mo`, the domain used before 4.0.0
    3. `languages/product-delivery-date-for-woocommerce-lite-{locale}.mo` in the plugin (Loco Translate's "Author" location)
    4. `WP_LANG_DIR/plugins/product-delivery-date-for-woocommerce-lite-{locale}.mo`, the wp.org language pack (Loco's "System" location)
  - For a string translated in more than one file, the file loaded first wins. WordPress alone would load only the language pack and skip the plugin's `languages/` folder.
  - Loco Translate's "Custom" location (`languages/loco/plugins`) is loaded by Loco itself, ahead of these.
- **Strings:**
  - PHP strings use `__()`, `esc_html__()` and similar.
  - React strings use `@wordpress/i18n` with the same text domain.
  - Both bundles call `wp_set_script_translations()` with `PRDD_LITE_PLUGIN_PATH . '/languages'`.
- **POT and the React strings:**
  - `npm run i18n:pot` reads `src/` and `build/`, so the React strings have `#: build/admin-settings.js` and `#: build/product-metabox.js` references. Run `npm run build` first.
  - Keep the `build/` references. WordPress loads the JSON file named after the md5 of the script path (`build/admin-settings.js`), and Loco Translate and `wp i18n make-json` name the JSON files after the references. With `src/` references alone, the JSON files get names WordPress never loads, and the React screens stay in English.
  - Strings shown in the React screens must go through `__()`, including attributes such as `placeholder` and `aria-label`, and the texts of third party libraries such as Choices.js.

---

## Coding standards and QA

- **PHP:** WordPress Coding Standards through [`tyche-phpcs.xml`](tyche-phpcs.xml).

  ```bash
  phpcs --standard=tyche-phpcs.xml includes/ class-prdd-lite-woocommerce.php
  php -l path/to/file.php
  ```

- **JS:** `npm run lint:js`, or `npx wp-scripts lint-js <file>` for one file.
- **Plugin Check:** run the WordPress.org [Plugin Check](https://wordpress.org/plugins/plugin-check/) before each release. It runs from WP-CLI without activating it. Run it from the site root (`app/public`):

  ```bash
  wp plugin check product-delivery-date-for-woocommerce-lite \
    --require=./wp-content/plugins/plugin-check/cli.php \
    --exclude-directories=node_modules,vendor,src \
    --format=csv --fields=line,type,code,message
  ```

  There must be no errors.
  - **Known warnings:** the prefix warnings for the shared Tyche components and older names:
    - classes: `Tyche_Plugin_Tracking`, `Tyche_Plugin_Deactivation`, `Ts_Upgrade_To_Pro_Prdd`;
    - hooks: the `ts_tracker_*` hooks;
    - functions: `is_prdd_lite_active`.

    Renaming these breaks compatibility.
  - **False positives:** two `UnescapedDBParameter` warnings in `class-prdd-lite-calendar-view.php`, where the queries are prepared.

---

## REST API

The namespace is **`prdd-lite/admin/v1`** (`PRDD_Lite_Admin_API::REST_NAMESPACE`), used by the React screens.

- Every route needs a logged-in user with `manage_woocommerce` (`admin_permission()`) and the `wp_rest` nonce.
- `src/shared/api.js` sends the requests with `@wordpress/api-fetch`.

| Method | Route | Purpose |
| --- | --- | --- |
| GET, POST | `/settings/general` | Settings screen |
| GET, POST | `/settings/holidays` | Global holidays |
| GET, POST | `/settings/appearance` | Calendar appearance |
| GET | `/view-deliveries` | View Deliveries list (rendered table HTML) |

These are the AJAX actions that are still in use:

| Action | Use |
| --- | --- |
| `prdd_calender_content` | Event popup content in the deliveries calendar |
| `prdd-adminend-events-jsons` (on `admin_init`, nonce checked) | Events JSON of the deliveries calendar |
| `prdd_lite_update_database` | Migration to Pro |

---

## Extending the plugin

### JavaScript filters (`@wordpress/hooks`)

| Filter | Arguments | Use |
| --- | --- | --- |
| `prdd-lite.admin.routes` | `routes[]` of `{ path, element }` | Add screens to the settings app |
| `prdd-lite.productMetabox.tabs` | `tabs[]` of `{ name, title, icon, Component }` | Add or change tabs of the product meta box |
| `prdd-lite.productMetabox.fields` | `null, name, form` | Render extra fields at a named position in the meta box |

```js
import { addFilter } from '@wordpress/hooks';

addFilter( 'prdd-lite.admin.routes', 'my-addon/routes', ( routes ) => [
	...routes,
	{ path: 'my-addon', element: <MyAddonScreen /> },
] );
```

### PHP filters and actions

| Hook | Type | Use |
| --- | --- | --- |
| `prdd_lite_admin_config` | filter | Change `window.prddLiteAdmin` |
| `prdd_lite_product_metabox_data` | filter | Change `window.prddLiteProductMetabox` |
| `prdd_lite_product_metabox_legacy_post` | filter | Change the values posted from the meta box JSON before they are saved |
| `prdd_lite_after_product_settings_saved` | action | Runs after a product's delivery settings are saved |
| `prdd_lite_additional_holidays` | filter | Add holidays to the product page calendar |
| `prdd_get_item_data` | filter | Change the delivery date shown in the cart and checkout |
| `prdd_lite_view_deliveries_table_columns` | filter | Columns of View Deliveries |
| `prdd_lite_view_deliveries_sortable_columns` | filter | Sortable columns of View Deliveries |
| `prdd_lite_delivery_table_column_default` | filter | Content of a custom column |
| `prdd_lite_deliveries_table_data` | filter | Rows of View Deliveries |
| `prdd_deliveries_table_views` | filter | Views (status links) of View Deliveries |
| `delivery_search` | action | Extra search fields on View Deliveries |
| `prdd_lite_add_submenu` | action | Add submenus under Product Delivery Date |
| `prdd_lite_add_meta_footer` | action | Output at the bottom of the product meta box (the review request uses it) |

Check the call site before you rely on a hook's arguments. Some of them are older hooks kept for add-ons.

---

## Release checklist

1. **Version:** bump it in the plugin header and in `PRDD_LITE_VERSION` (`product-delivery-date-for-woocommerce-lite.php`), and update `Stable tag` in `readme.txt`.
2. **Tested up to:** update `Tested up to` (WordPress) in `readme.txt` and `WC tested up to` in the plugin header.
3. **Changelog:** add the entry to `readme.txt` and to `changelog.txt`. Use the `Fix -`, `Tweak -` and `Enhancement -` prefixes.
4. **Build:** run `npm run build` and commit `build/`.
5. **POT:** run `npm run i18n:pot`.
6. **Lint:**
   - `npm run lint:js`;
   - `phpcs --standard=tyche-phpcs.xml`;
   - `php -l` on the changed files.
7. **Plugin Check:** run it as shown above. There must be 0 errors.
8. **Smoke test on a clean site:**
   - save every live settings screen;
   - save a product with the delivery date enabled;
   - order a simple and a variable product on both the classic and the block checkout, and check the `Delivery Date` / `_prdd_lite_date` / `_prdd_date` item meta;
   - check View Deliveries (list and calendar);
   - open each locked preview;
   - run the migration on a site with Pro.
9. **Package:** leave out the files that are only used in development. There is no `.distignore` yet, so leave these out by hand:
   - `node_modules/`
   - `src/`
   - `package*.json`
   - `Dangerfile`
   - `Gemfile`
   - `tyche-phpcs.xml`
   - `README.md`
10. **WordPress.org:** tag the release in WordPress.org SVN, then update the assets and screenshots if they changed.

---

## Troubleshooting

| Problem | Check |
| --- | --- |
| Admin page is blank | `build/admin-settings.js` is missing or out of date. Run `npm run build`. Check the browser console for errors from `ErrorBoundary`. |
| REST requests return 401/403 | The user needs `manage_woocommerce`. The `wp_rest` nonce has expired, so reload the page. |
| Delivery field doesn't show on the product | `Enable Delivery Date` is off for the product, or the theme doesn't fire `woocommerce_before_add_to_cart_button`. |
| No date can be picked | All weekdays are off, or the minimum delivery time and the holidays block every date within the maximum number of days. |
| Date missing on the order item | Check that something else doesn't remove `prdd_lite_delivery` from the cart item, and that `woocommerce_checkout_create_order_line_item` fires (custom checkouts). |
| React strings not translated | See [Translations](#translations). Check that the JSON file names match the `prdd-lite-admin-settings` / product metabox handles. |
| Migration notice keeps showing | `prdd_is_data_migrated` is not `done`. Run the update, and check the AJAX response in the Network tab. |
