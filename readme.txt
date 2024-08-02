=== Custom Checkout Fields for WooCommerce ===
Contributors: wpcodefactory, algoritmika, anbinder, karzin, omardabbas, kousikmukherjeeli
Tags: woocommerce, checkout, woo commerce
Requires at least: 4.4
Tested up to: 6.6
Stable tag: 1.8.3
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add custom fields to WooCommerce checkout page.

== Description ==

**Custom Checkout Fields for WooCommerce** plugin lets you add custom fields to WooCommerce checkout.

= &#9989; Field Types =

* Text
* Textarea
* Number
* Checkbox
* Color
* Datepicker
* Weekpicker
* Timepicker
* Select (including Select2)
* Multiselect (including Select2)
* Radio
* Password
* Country
* State
* Email
* Phone
* Search
* URL
* Range

= &#9989; General Options =

* Label
* Placeholder
* Default value
* Description
* Required
* Customer meta fields

= &#9989; Position Options =

* Section (billing/shipping/account/order)
* Priority (i.e. order)

= &#9989; Input Options =

* Max length
* Min value
* Max value
* Step
* Autofocus
* Autocomplete

= &#9989; Style Options =

* Class
* Label class
* Input class

= &#9989; Visibility Options =

* Product categories
* Product tags
* Products
* User roles
* Min cart amount
* Max cart amount
* Product shipping classes
* Virtual products
* Downloadable products
* Countries

= &#9989; Fee Options =

* Fee value
* Fee type (fixed; percent)
* Fee title
* Is fee taxable

= &#9989; Feedback =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/custom-checkout-fields-for-woocommerce/).

= &#8505; More =

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Custom Checkout Fields".

== Screenshots ==

1. Field options.

== Changelog ==

= 1.8.3 - 02/08/2024 =
* Dev - Visibility Options - By another field - "Action" options added ("Check if not empty" and "Check for an exact value").
* WooCommerce added to the "Requires Plugins" (plugin header).

= 1.8.2 - 30/07/2024 =
* WC tested up to: 9.1.
* Tested up to: 6.6.

= 1.8.1 - 24/11/2023 =
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* WC tested up to: 8.3.
* Tested up to: 6.4.

= 1.8.0 - 02/11/2023 =
* Fix - Duplicate - Extra/unnecessary field removed.
* Dev – "High-Performance Order Storage (HPOS)" compatibility.
* WC tested up to: 8.2.

= 1.7.6 - 03/10/2023 =
* Plugin author updated.

= 1.7.5 - 24/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.
* Plugin icon, banner updated.

= 1.7.4 - 18/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 1.7.3 - 08/09/2022 =
* Fix - Possible "Uncaught Error: Call to a member function get_cart() on null in class-alg-wc-ccf-frontend.php:596" fixed.
* Dev - Deploy script added.
* WC tested up to: 6.8.

= 1.7.2 - 16/07/2022 =
* Dev - Visibility Options - Min/Max cart amount - "Add shipping cost to cart total" option added.
* WC tested up to: 6.7.
* Tested up to: 6.0.

= 1.7.1 - 13/04/2022 =
* Tested up to: 5.9.
* WC tested up to: 6.4.

= 1.7.0 - 24/12/2021 =
* Dev - Type - "Multiselect" type added.
* WC tested up to: 6.0.

= 1.6.5 - 04/10/2021 =
* Dev - Visibility Options - Products - Product variations added.
* Dev - Visibility Options - Products - Now using AJAX product search in admin settings.
* Dev - Code refactoring.
* WC tested up to: 5.7.

= 1.6.4 - 06/09/2021 =
* Fix - "Account" and "Order notes" section fields were duplicated to other sections as well. This is fixed now.

= 1.6.3 - 02/09/2021 =
* Fix - Visibility Options - Checking for `is_visible()` in `default_address_fields()`. This fixes the issue with visibility options (e.g. "User roles") not functioning properly.
* Dev - Visibility Options - "By another field" option added.
* Dev - General - "Hide unrelated type options" option added (defaults to `no`).
* Dev - General - "Fields ID" option added (defaults to `alg_wc_checkout_field`).
* Dev - Admin settings rearranged - type related options moved up.

= 1.6.2 - 25/08/2021 =
* Fix - Visibility Options - Countries - Required/optional bug fixed.
* WC tested up to: 5.6.

= 1.6.1 - 30/07/2021 =
* Dev - Shortcodes are now applied in "Datepicker > Min date" and "Datepicker > Max date" options (and field types changed from number to text).
* Dev - `[alg_wc_ccf_if]` shortcode added.
* Dev - `[alg_wc_ccf_datetime]` shortcode added.
* WC tested up to: 5.5.
* Tested up to: 5.8.

= 1.6.0 - 30/06/2021 =
* Fix - Fees - JS script fixed.
* Dev - "Duplicate" option added.
* Dev - Admin order details - Stripping tags from field labels now.
* Dev - Position Options - Priority (i.e. order) - Default value updated to `200` (was `0`). Admin settings description updated.
* Dev - Developers - `alg_wc_ccf_field_visible` filter added.
* Dev - Plugin is initialized on `plugins_loaded` action now.
* Dev - Code refactoring.
* WC tested up to: 5.4.

= 1.5.1 - 25/03/2021 =
* Dev - "Prepopulate default value" option added.
* Dev - Admin settings restyled; descriptions updated; "Customer meta fields" option renamed to "User profile".
* WC tested up to: 5.1.
* Tested up to: 5.7.

= 1.5.0 - 06/01/2021 =
* Dev - Datepicker/Weekpicker Type Options - Datepicker: Timepicker addon - "Custom text" options added.
* Developers - `alg_wc_ccf_get_field_option` filter added.
* Dev - Localization - `load_plugin_textdomain` moved to the to `init` action.

= 1.4.9 - 16/12/2020 =
* Dev - Frontend - Order meta - Checking if field `is_visible()` before adding it to order meta. This solves the issue with hidden (e.g. via "Visibility Options > Products") "checkbox" type fields.
* Tested up to: 5.6.
* WC tested up to: 4.8.

= 1.4.8 - 05/11/2020 =
* Dev - `[alg_wc_ccf_translate]` shortcode added (for WPML / Polylang).
* Dev - Shortcodes are now processed in these options: Label, Placeholder, Default value, Description, Fee title, Value for ON, Value for OFF.

= 1.4.7 - 28/10/2020 =
* Dev - Select/Radio Type Options - Select2 - "Text input by user" option added.
* WC tested up to: 4.6.

= 1.4.6 - 07/10/2020 =
* Dev - Using another algorithm for JS minification now.
* WC tested up to: 4.5.

= 1.4.5 - 19/08/2020 =
* Dev - Datepicker/Weekpicker Type Options - Datepicker: Timepicker addon - "Time format" option added.
* Dev - JS files minified.
* WC tested up to: 4.4.

= 1.4.4 - 17/08/2020 =
* Fix - Datepicker/Weekpicker Type Options - Datepicker: Exclude months - Fixed for 2-digit months.
* Dev - Datepicker/Weekpicker Type Options - "Datepicker: Exclude dates" option added.
* Dev - Datepicker/Weekpicker Type Options - Datepicker: Timepicker addon - "Min time" and "Max time" options added.
* Dev - Timepicker Type Options - "Min time" and "Max time" options added.

= 1.4.3 - 14/08/2020 =
* Fix - Weekpicker - JS errors fixed.
* Dev - Datepicker/Weekpicker Type Options - "Datepicker: Timepicker addon" option added.
* Dev - Datepicker/Weekpicker Type Options - Settings titles updated for "Exclude days" and "Exclude months" options (titles start with "Datepicker: ..." now).

= 1.4.2 - 13/08/2020 =
* Dev - Datepicker/Weekpicker Type Options - "Exclude days" option added.
* Dev - Datepicker/Weekpicker Type Options - "Exclude months" option added.
* Tested up to: 5.5.

= 1.4.1 - 07/08/2020 =
* Fix - Advanced - Force fields sort by priority - Option fixed.
* Dev - Select/Radio Type Options - Select2 - "Custom text" options added.
* WC tested up to: 4.3.
* Tested up to: 5.4.

= 1.4.0 - 02/03/2020 =
* Dev - Visibility Options - "Countries" ("Hide" or "Show") options added.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.
* WC tested up to: 3.9.

= 1.3.0 - 13/11/2019 =
* Dev - Admin settings restyled.
* Dev - Code refactoring.
* Langs - `es_ES` translation added.
* Tested up to: 5.3.
* WC tested up to: 3.8.

= 1.2.1 - 22/05/2019 =
* Dev - Visibility Options - "Virtual products" option added.
* Dev - Visibility Options - "Downloadable products" option added.
* Dev - Fee Options - "Percent fee: Cart total" option added.
* Dev - Fee Options - "Percent fee: Add shipping" option added.

= 1.2.0 - 20/05/2019 =
* Dev - "Fee Options" added.

= 1.1.0 - 09/05/2019 =
* Fix - Plugin URI fixed.
* Dev - Visibility Options - "Product shipping classes" option added.
* Dev - Code refactoring.
* Tested up to: 5.2.
* WC tested up to: 3.6.

= 1.0.0 - 04/05/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
