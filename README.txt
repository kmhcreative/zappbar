=== ZappBar ===
Author URI: http://www.kmhcreative.com
Plugin URI: https://github.com/kmhcreative/zappbar
Contributors: OffWorld, Frumph
Tags: Responsive, Mobile, Theme, Modifications
Requires at least: 3.5
Requires PHP: 5.3
Tested up to: 6.1.1
Stable Tag: 0.2.8
License: GPLv3
Licence URI: http://www.gnu.org/licenses/gpl-3.0.html

Automagically adds responsive, customizable mobile UI to (almost) any WordPress theme.

=== Description ===

I got tired of making the _same_ responsive layout modifications to different WordPress themes over and over again and I wanted an easy way to create and modify a custom interface for mobile devices.  So I rolled all the stuff I was doing manually into a single plugin! ZappBar adds highly configurable mobile app style button bars to the top and bottom of any WordPress theme.  You can decide at what screen/browser widths they activate (so you can show them only for phones or tablets without altering your site for desktop users).  This plugin can also *try* to convert non-responsive themes to responsive layouts that auto-adjust for mobile devices and it can *try* to fix some mobile layout issues that bugged me about the WordPress admin backend too.

== Beta Version Disclaimer ==

This plugin is still being tested.  Do not use it in production unless you are willing to accept the possibility that it could screw up your website while activated.  The alterations are non-destructive, so if it causes problems for you deactivating this plugin should restore your site to the way it was.

== Installation ==

= Using Admin Upload =

1. Download the GitHub archive as a ZIP file.
2. Go to your _Dashboard > Plugins > Add New_ and press the "Upload Plugin" at the top of the page.
3. Browse to where you downloaded the ZIP file and select it.
4. Press the "Install Now" button.
5. On your _Dashboard > Plugins_ page activate "ZappBar"
6. Go to _Dashboard > Settings > Zappbar_ and configure it.

= Using FTP =
  
2. Unzip it so you have a "zappbar-master" folder
3. FTP upload it into your WordPress blog’s _~/wp-content/plugins/_ folder.
4. Go to your _Dashboard > Plugins_ and activate “ZappBar”
5. Go to _Dashboard > Settings > ZappBar_ to configure it.


== FAQ ==

= What if the theme is already responsive? =
If you set it to show ZappBars on all screen sizes/devices you may still need to select *Site Layout > Theme Layout > Retrofit to responsive* even if the theme is already responsive.  Try it first and see if it works better with Retrofit or not.

= Do the WooCommerce buttons work everywhere on my site? =
If you are using the WooCommerce plugin be aware the WooCart, WooAccount, etc., button actions ONLY work on WooCommerce-enabled pages.  With themes that do not have built-in support for WooCommerce you can only use those actions on the pages the WooCommerce plugin creates. On other pages you can still have "Cart" and "Account" buttons, but the actions will have to link them to the actual PAGES and *not* the "Woo" functions.  Consequently the Cart icon will only show the number of items and total on WooCommerce-enabled pages, elsewhere it will simply be an icon with no dynamic elements.

= How does the "All Blog Posts" option work? =
The "All Blog Posts" option in the button actions will show an ascending list of all blog posts in all categories.  If what you want is to show blog posts in descending order (newest to oldest) create a page named "Blog" and point the button action to that page instead of "All Blog Posts."

= What are the Custom Elements for under Site Layout? =
The first three settings under "Site Layout" are very inter-related. If retro-fitting a theme you may have to try different combinations to find the right ones if the site, or parts of the site are not becoming responsive.  You may also need to enter custom target element IDs or classes if the theme uses custom ones.  ZappBar was designed for use with child themes based on the default WordPress, ComicPress, Inkblot, Webcomic, or WooCommerce themes.  So if you're using a child theme of some *other* base theme, you will likely need to customize the target settings to get retrofitting to work.

= Why won't my ZappBar buttons point to certain pages? =
Depending on what other plugins you have activated that ZappBar interacts with, you may need to go to *Dashboard > Settings > Permalinks* after activating/deactivating ZappBar.  ZappBar doesn't create any custom post types or rewrite rules but it does utilize those created by WooCommerce and any of the comic plugins.

= Why won't ZappBar work in the Theme Customizer? =
Zappbar buttons are not functional when shown in Theme "Customize" interface because they are only activated on the front-end of your website.  Configure ZappBar under Settings > ZappBar.

== Changelog ==

= Version 0.2.8 =
* Added Tumblr to Social Sharing options.
* Added Mastodon to Social Sharing options.
* Added Mastodon Verification Code insertion option.
* Added "Share on Mastodon Easily" script.
* Social Media options now sorted alphabetically.

= Version 0.2.7 =
* Fixed issue with icon picker pop-up not populating with icons to pick.
* Fixed Splash Timer error if database had no saved timeout value.

= Version 0.2.6 =
* Share Panel now works on Archives and Search pages
* zb_share shortcode now works on Archives and Search pages
* added heading to settings page
* automatically updates database with new options on activation
* fixed unexpected character output on activation caused by version check
* Version check now also sees if minimum PHP requirement is met
* fixed javascript error in browser detection for Nexus 5X
* fixed Icon Picker box width
* fixed PHP Notice about social media index being undefined
* fixed compatibility with Webcomic plugin version 5
* fixed compatibility with MangaPress plugin version 3 and 4
* fixed compatibility with WooCommerce plugin version 4.3
* ZappBar WooCommerce bar now shows on "Shop" page too
* ZappBar Options Layout now shows bars 375 pixels wide because almost nobody is still using a device with a 320 pixel wide portrait screen.
* ZappBars are now 50px high, which is as close to a standard navbar/toolbar height as there is.
* ZappBar buttons are now 50px high and 64px wide, which will hopefully stop Google's Smartphone crawler from complaining about touch-targets being too small.  64px wide buttons will still all fit on 320px wide screens.
* ZappBar button label text is now 12px because that's the minimum acceptable size for Google's Mobile-First indexing, and will hopefully get rid of the "text too small" warnings.
* ZappBar button labels can now be turned off for a cleaner no-text look (but if you suppress button labels make sure you use an icon that makes it really obvious what the button does).
* ZappBar Panels can now inherit styling from the theme stylesheet
* Fixed ZappBar Panel links from having ZappBar button styles applied to them.
* Tweak Admin styles relevant to ZappBar's settings now part of regular admin styles.  Tweak Admin Styles now only fixes Admin Bar and slide-in menu.  Description updated to reflect it is to make backend more PHONE friendly.


= Version 0.2.5 =
* Updated browser sniffer for Edge Chromium
* Added option to turn full Comments Section into App Panel
* Added Auto-Updating directly from GitHub (no longer requires GitHub Updater plugin)
* Tested compatible up to PHP 7.1.31
* Google Plus removed from Social Media options.
* If ONLY the Comment Form is turned into an App Panel the Reply-To links no longer move the form but it still posts to correct place in comment thread.

= Version 0.2.4 =
* Fixed Google Translate widget which didn't work unless using old ComicPress theme.

= Version 0.2.3 =
* Removed Digg from Social Media options
* Removed StumbleUpon from Social Media options
* Fixed issue where inactive ZappBar buttons caused 404 Not Found errors for bots indexing site.  The button href placeholders were mistaken for links to non-existent pages.  They are now masked as in-page anchor links.
* Updated zappbar.js to find and activate buttons via the fake anchor links. 

= Version 0.2.2 =
* Fixed error where ComicPress sidebars would be displayed on iPad in portrait even when set to be hidden.
* Fixed error where ComicPress comic post was using comic bottom bar when set to use default one.
* Fixed minor sidebar display errors when "Retrofit" is applied to a ComicPress theme.
* Fixed issue where Comment box converted to Zappbar Panel was not kicking in for iPad in landscape.
* Removed Delicious from Social Media options
* Added option to Reset all settings to defaults
* Added option to hide Blog Navigation links
* Added options to selectively NOT display top/bottom Zappbar(s)
* Added options for custom top/bottom ZappBars on single Blog Post
* Added disabled button styling to blog, comic, and archive pages where you are already on the first or last page/post/chapter.
* Zappbar now checks Woocommerce product pages for the existence of elements converted to panels and disables buttons wired to them in the event they do not exist.
* Fixed error in Site Layout that always showed Woocommerce tab-to-panel conversion enabled, even when it wasn't.
* If comment form is converted to panel, Reply-to links can now also open/close it.
* Added setting to make using App Icon as site Favicon optional (ZappBar gets added to head tag late, so it over-rides any favicon set by theme, but not everyone wants this).
* Fixed problem with some settings going back to default on re-activation.
* Combined WP version compatibility check with other activation hook functions

= Version 0.2.1 =
* Fixed problem on search results pages where nothing is found where social media share panel tried to build but can't because $post doesn't exist.

= Version 0.2 =
* [Issue #1] Responsive stylesheets updated to support HD Android and Surface tablets, iPhone 6.  Application is now a two-step process by screen size and/or device
* [Issue #2] Disable Admin Toolbar no longer disabled by default.
* [Issue #3] Show/Hide/Convert theme elements overhauled (now allows deselecting all), `zappbar.js` script updated with better apply/unapply action.
* [Issue #4] Updated to PHP 5 Constructors
* [Issue #4] Fixed numerous Notices of undefined variables
* Added “Force” and “Only” over-rides for Mobile devices so you can now (fairly) reliably apply it just to phones and tablet while leaving your Desktop theme untouched.
* Added “retrofit” option to try to make non-responsive themes responsive even when ZappBars are not being shown
* Added option to customize when "retrofit" kicks-in linearization
* `zappbar.js` script updated with better device detection.
* Fixed “Hide Comic Navigation” checkbox
* Added github-updater plugin code to plugin header
* Responsive layout fixes for ComicPress 4.3
* Auto-detect theme width for retrofit added
* Fixed misaligned comic post receiving comic archive styling
* Fixed undefined index social_panel and settings
* Fixed missing default button bg opacity variable on activation
* Cleaned up sprintf in `class.settings-api.php`
* Added type for HTML5 input type="number" to `class.settings-api.php`
* Fixed inline styling of ZappBar menu items
* Fixed `#wpadminbar` and sidebar display issues with `sample_styles.css` file
* Merged in fixes to `html_inject.php` for ComicPress chapter navigation

= Version 0.1 =
Initial public release.

== Resources ==

This uses my [Icon Picker](https://github.com/kmhcreative/icon-picker) (which is based on [Dashicons Picker](https://github.com/bradvin/dashicons-picker) by Brad Vincent) to get/set the icons on the ZappBars.

[ComicPress 2.9 Theme](http://comicpress.org/)

[ComicPress 4.x Theme](https://github.com/Frumph/comicpress)

[Webcomic Plugin](https://wordpress.org/plugins/webcomic/)

[Manga+Press Plugin](https://wordpress.org/plugins/mangapress/)

[WooCommerce Plugin](https://wordpress.org/plugins/woocommerce/)

== Upgrade Notice ==
= 0.2.6 =
Non-critical update. Social Media sharing functions now work on Archive and Search pages.  Note that you may need to Save settings after updating.

