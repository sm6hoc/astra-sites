=== Astra Starter Sites ===
Contributors: brainstormforce
Donate link: https://wpastra.com/pro/
Tags: demo, theme demos, one click import
Requires at least: 4.4
Requires PHP: 5.3
Tested up to: 5.0.2
Stable tag: 1.2.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Import Astra Starter Sites with just one click.

== Description ==

This plugin is an add-on for the Astra WordPress Theme. It offers a library of ready sites that can be imported for your website easily. Here is how it works:

1. Browse through the library of ready sites right from your WordPress backend.
2. Pick a site you like.
3. Install required plugins in one click
4. Import the site data.
5. Done ;)


Use this imported site as a base for your project and don't waste time starting from scratch!

_<a href="https://wpastra.com/ready-websites/">See list of all available sites to import »</a>_

#### Video Walkthrough by Adam from WPCrafter:
[youtube https://www.youtube.com/watch?v=zYbz-jxE9_Q]

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/astra-sites` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to Appearance -> Astra Sites to preview and import sites.

== Frequently Asked Questions ==

= Is this really free? =

Yup, we have dozens of free websites ready to import! We have a premium version as well that is required for importing premium sites.

Learn More: https://wpastra.com/agency/

= Can I suggest new websites that you I add? =

Sure. We love suggestions! Please submit them here -
https://wpastra.com/sites-suggestions/


== Screenshots ==

1. Select the demo you want to import.
2. Install and activate the required plugins.
3. Import the demo.

== Changelog ==

v1.2.10 - 4-Jan-2019
* Fix: Astra Sites not accessible due to incorrect query parameters.

v1.2.9 - 17-Dec-2018
* Fix: XML not import due to getting different MIME file types on different PHP versions.

v1.2.8 - 14-Dec-2018
* Improvement: Added admin notice if XMLReader is not installed. XMLReader is required to import the XML of the website.
* Fix: XML not import due to improved verification of MIME file types in WordPress v5.0.1.

v1.2.7 - 12-July-2018
* Improvement: Added page builder and category filter support to show selected page builder with selected categories.
* Improvement: Added filter `astra_sites_show_filters` to enable/disable the filter list from admin page.

v1.2.6 - 9-July-2018
* Fix: We have added `wp_slash` to normalize the Elementor post meta. Elementor have also normalize it. So, We have avoided `wp_slash` while importing sites.

v1.2.5 - 5-July-2018
* Fix: Normalize Elementor post meta by using `wp_slash` to avoid the unslashing data.

v1.2.4 - 29-June-2018
* Improvement: Removed custom license validation form and used Graupi in-build license validation form.
* Improvement: Set default page builders depends on the packaged purchase.

v1.2.3 - 13-June-2018
* Improvement: Added windows EDGE browser support for importing the sites.
* Improvement: The log file was not created if server does not support the file handling functions.
* Fix: Load WXR importer on init to avoid redirect loop when loading WooCommerce importer.
* Fix: Clear the Astra Pro plugin cache after site import.

v1.2.2 - 26-March-2018
* Fix: Correctly load the Elementor Pro 2.0 compatibility class for beta versions.

v1.2.1 - 23-March-2018
* Improvement: Clear the Astra Pro plugin cache after site import.

v1.2.0 - 22-March-2018
* Improvement: Added compatibility for Elemetor version 2.0.0. Older versions throw the PHP warning for function process_element_export_import_content().

v1.1.9 - 12-March-2018
* Fix: String `Select Your Favorite Page Builder` jerk while loading the sites.

v1.1.8 - 5-March-2018
* Improvements: Updated processing button animation while installation/activating plugin and importing site.
* Improvements: Updated suggestion box message.
* Fix: PHP fatal errors for WXR importer classes `WXR_Import_Info`, `WXR_Importer`, `WP_Importer_Logger_ServerSentEvents` and `WP_Importer_Logger`.

v1.1.7 - 2-February-2018
* Improvements: Some users reported confusion in the default option of choosing page builder. We have made UX improvements so users now must select the Page Builder first before selecting any website.

v1.1.6 - 22-January-2018
* New: Added filter `astra_sites_xml_import_options` to change the XML import options.
* Fix: Astra Pro plugin 'Custom Layouts' & 'Page Headers' not setting right display location due to different page, tax, category ids.
* Fix: WooCommerce shop, checkout cart page ids not setting issue.
* Fix: After site import updated demo url from the nav menus.

v1.1.5 - 11-January-2018
* New: Added SVG file support for importing the SVG images.

v1.1.4 - 28-Dec-2017
* Improvement: Importing WooCommerce product category images.
* Improvement: Retain WooCommerce cart, checkout & my account pages when importing the ready WooCommerce sites.
* Fix: Disabled WooCommerce plugin setup wizard after plugin install & activate.

v1.1.3 - 20-Dec-2017
* Improvement: Retain WooCommerce shop page when importing the ready WooCommerce sites.

v1.1.2 - 24-Nov-2017
* Fix: Handling plugin installation errors.

v1.1.1 - 23-Nov-2017
* New: Change the api url for Astra sites to https://websitedemos.net/ from https://sites.wpastra.com/

v1.1.0 - 21-Nov-2017
* New: Import the site content using Event Source (SSE) which ensures faithful imports.
* New: Divided the site import process in separate AJAX calls to reduce the possibility of timeouts.
* New: Generated the import log file. It will be displayed in the UI if the import fails.
* Improvement: Validate all the possible errors.
* Improvement: Updated Astra sites HTML grid structure for WordPress v4.9 compatibility.
* Enhancement: Updated plugin name from Astra Sites - Lite with Astra Starter Sites.

v1.0.14 - 9-Nov-2017
* New: All the linked images on the Astra Sites will be downloaded to your site, No more loading images from external URLs.
* New: Added suggestion box at as the last column in when listing sites so that you can add a suggest the sites you want.
* New: Added site responsive preview buttons.
* Improvement: Search string will not be removed when switching the page builder when scrolling through sites.
* Improvement: Loading 15 sites instead of 6 Astra sites in the first load.
* Improvement: Removed LazyLoad which is not useful in admin back-end for showing Astra Sites.

v1.0.13 - 9-Oct-2017
* New: Browsing the Astra Sites in the Admin panel is not faster with JS rendering.

v1.0.12 - 29-Sept-2017
* New: Added White Label support from <a href="https://wpastra.com/pro/">Astra Pro</a>.
* Improvement: Don't display sites from both the page builders in the same view.
* Fix: Astra Sites admin area not working in the Firefox.

v1.0.11 - 22-Sept-2017
* New: Single click Install & activate required plugins.
* New: Added filter `astra_sites_menu_item` for adding extra tabs in admin page.
* New: Added back image import feature for `elementor` page builder. In batch image import we import all images from astra site into client site.
* Improvement: Updated JS code with object prototype.
* Fix: Screen bounce on retina devices.

v1.0.10 - 11-Sept-2017
* Improvement: Added support for retina logo import.
* Fix: Site logo image not displayed in customizer.
* Fix: Updated `Astra Agency` purchase link.

v1.0.9 - 8-Sept-2017
* New: Added page builder categories for listing sites as per page builder.

v1.0.8 - 6-Sept-2017
* Fix: Beaver Builder option import.
* Enhancement: Disabled dismiss-able notice visible once for each user.
* Enhancement: Showing error message for for user who have not `manage_plugins` capability.

v1.0.7 - 1-Sept-2017
* Fix: Custom Menu widget not setting imported widget.

v1.0.6 - 30-Aug-2017
* New: Addd custom menu for Astra Sites.
* Fix: Validate site options before storing in database.

v1.0.5 - 29-Aug-2017
* New: Added filter `astra_sites_api_args` for adding extra arguments in api call.
* Enhancement: Plugin name updated from `Astra Sites` with `Astra Free Sites`.
* Fix: PHP error while ignoring users.

v1.0.4 - 21-Aug-2017
* New: Added filter `astra_sites_api_params` for adding extra params in api call.
* New: Added filter `astra_sites_api_args` for adding extra arguments in api call.
* New: Added filter `astra_sites_category_hide_empty` for showing categories which are not set for any site.

v1.0.3 - 11-Aug-2017
* Fix: Avoided Astra users from site import process.

v1.0.2 - 09-Aug-2017
* Fix: Listing appropriate next and previous Astra sites.
* Enhancement: Listing Astra sites though AJAX API call.

v1.0.1 - 04-Aug-2017
* New: Added Elementor plugin options support.
* New: Added Customizer CSS support.
* Enhancement: Avoided Lite Plugin version if Pro version is Installed. Now added support for Beaver Builder Plugin (Lite Version).
* Enhancement: Astra sites API call validated before import.
* Enhancement: Site logo imported from Astra sites.
* Fix: Bug where widgets created with SiteOrigin plugin were not being imported.

v1.0.0
* Initial release
