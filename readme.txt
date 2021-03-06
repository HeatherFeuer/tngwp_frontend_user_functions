=== TNG-WP Frontend User Functions ===
Contributors: HeatherFeuer
Tags: profile, TNG, login, registration
Requires at least: 3.0
Tested up to: 4.1.1
Stable tag: 3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adapts the WordPress User Profile to accommodate additional TNG information for use with custom registration forms. It provides shortcodes to display two levels of user registration, a front-end profile page, and a one-line login form to place in a template. There is also a sidebar login/logout widget. New registrations are are seamlessly integrated with TNG. Version 2.0 is a complete re-write of the plugin so you will need to completely uninstall the old plugin before installing this one. 

== Description ==

This plugin is specifically designed to work as a user interface for Wordpress/TNG integrations. It provides shortcodes for registration, a front-end profile page, a one-line login/logout shortcode that can be placed in your template using the <pre>do_shortcode();</pre> function, and a login/logout widget. The plugin also includes a built-in captcha for added security.

Please note that this plugin requires [The Next Generation of Genealogy Sitebuilding](http://www.tngsitebuilding.com/) however, the tng-wordpress-plugin is no longer required. This plugin will now work as a stand-alone solution for any form of integration used.

== Installation ==

* Download the zip file [tng_user_meta_2-0.zip] (https://www.dropbox.com/s/rb40njfsywaxhn2/tng_user_meta_2-0.zip) to your computer.
* In Plugins --> Add New, select 'upload,' browse to the file you downloaded and click on 'Install Now.'
* Activate the plugin through the 'Plugins' menu in WordPress
* The Settings page is located under the Users menu in the Admin dashboard.

== Screenshots ==

1. Simple Registration form
2. Advanced Registration Search Ancestor form
3. Advanced Registration Search Results
4. Advanced Registration Form
5. Profile Page

== Changelog ==
= 3.0 =
* Added login/logout/delete user functions so that the standard TNG plugin is no longer required.
* Changed validation for captcha so that the submit button is no longer disabled.
= 2.1 =
* Fixed bug in captcha form so that submit button becomes enabled after correct captcha entered.
= 2.0 =
* Upgraded captcha to a more secure version.
* Added function to process lost password.
* Added function to log user IP and notify admin when users log in. This feature can be turned off if not wanted.
* Added autoupdate feature.
* Rewritten to make code standards-compliant.
= 1.5 =
* Bug fixes
* Additional styling
= 1.0 =
* Initial Release