=== Veloria Widgets for Elementor ===
Contributors: elementrify
Tags: elementor, page builder, widgets, woocommerce, charts
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A large library of Elementor widgets plus site-wide extensions: content and layout blocks, creative effects, marketing tools, data visualizations, and WooCommerce components—all manageable from the Veloria admin.

== Description ==

Veloria extends the Elementor page builder with dozens of widgets grouped into clear categories, optional extensions that apply across your site, and a dedicated settings area to enable only what you need.

**Content widgets**

Accordion, Advanced Google Map, Advanced Heading, Advanced Tabs, Animated Text, Badge, Blockquote, Button, Code Snippet, Content Toggle, Divider, Dropcap, Hero Slider, Highlight, Icon Box, Icon List, Image Box, Modal Popup, Navigation Menu, Off Canvas Content, Smart Post List, Spacer, Text Editor, Video Gallery, and Video Player.

**Creative widgets**

Animated Counter, Animated Gradient, Animated SVG, Before & After Slider, Bento Grid, Circular Progress, Countdown Timer, Dual Heading, Flip Box, Image Carousel, Image Gallery, Image Hotspot, Lottie Animation, Progress Bar, Star Rating, Timeline, and Tooltip.

**Marketing widgets**

Author Box, Banner, Call to Action, Contact Form, Contact Form 7 Styler, Instagram Feed, Logo Showcase, Price List, Pricing Table, Promo Box, Social Share, Team Members, Testimonials, USP Items, and Twitter Feed.

**Data and chart widgets**

Advanced Analytics, Area Chart, Bar Chart, Data Table, Donut Chart, Line Chart, Pie Chart, and Radar Chart.

**WooCommerce widgets** (when WooCommerce is active)

Add to Cart, Mini Cart, Product Carousel, Product Categories, Product Filter, Product Grid, Product Image, Product Tabs, WooCommerce Checkout, Customer Dashboard, and Thank You page layouts.

**Extensions**

Optional features such as smooth scrolling, layout duplication, a reading progress bar, scroll-to-top control, particle backgrounds, and moving circle effects—toggle them on or off from the plugin dashboard.

**Requirements**

* WordPress 6.0 or newer
* PHP 7.4 or newer
* Elementor (free or Pro) must be installed and active

WooCommerce-related widgets are available when WooCommerce is installed. Some Contact Form 7 styling options need Contact Form 7. Additional Pro widgets are available separately at https://elementrify.com/.

== Installation ==

1. Upload the `veloria` folder to `/wp-content/plugins/`, or install the ZIP from the Plugins → Add New screen.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Ensure **Elementor** is installed and activated.
4. Open **Veloria** in the admin menu to configure active widgets and extensions, then edit any page with Elementor and find **Veloria** widgets in the panel.

== Frequently Asked Questions ==

= Does Veloria work without Elementor? =

No. Veloria registers widgets for Elementor. Install and activate Elementor first.

= Do I need WooCommerce? =

Only if you want to use the WooCommerce-specific widgets. All other widgets work on standard sites.

= Where do I enable or disable individual widgets? =

Use the Veloria admin screens to control which widgets and extensions are active for your site.

= Is Elementor Pro required? =

No. Veloria is built for the Elementor editor; Elementor Pro is optional unless your own layout relies on Pro-only Elementor features.

== Screenshots ==

1. Veloria widgets in the Elementor panel under the Veloria category.
2. Admin area for managing widgets and extensions.
3. Example: charts and data widgets on the front end.
4. Example: WooCommerce widgets in a store layout.

== Changelog ==

= 1.0.0 =
* Initial release with Elementor widgets, extensions, and admin integration.

== Upgrade Notice ==

= 1.0.0 =
Initial public release.

== External services ==

This plugin can connect to third-party services when the related widget or feature is configured.

**Google reCAPTCHA** — Used by the Contact Form widget for spam protection when enabled in widget settings and API keys are saved under Veloria → Settings. Sends the reCAPTCHA response token and site verification request to Google when a form is submitted. Terms: https://policies.google.com/terms — Privacy: https://policies.google.com/privacy

**Instagram Graph API** — Used by the Instagram Feed widget when an access token is configured. Sends the stored access token to Meta/Instagram servers to fetch media for display. Terms: https://www.facebook.com/terms.php — Privacy: https://www.facebook.com/privacy/policy

**Twitter/X API** — Used by the Twitter Feed widget when a bearer token is configured. Sends API requests to api.twitter.com to fetch timeline data. Terms: https://x.com/en/tos — Privacy: https://x.com/en/privacy

**Freemius** — Optional SDK used for plugin updates, licensing, and upgrade flows after the administrator accepts the Freemius connection prompt. Sends site URL, plugin version, and license-related data when connected. Terms: https://freemius.com/terms/ — Privacy: https://freemius.com/privacy/
