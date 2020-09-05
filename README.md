# ZappBar

Automagically adds responsive, customizable mobile UI to (almost) any WordPress theme.

**Version:** 0.2.6

**Requires WordPress Version:** 3.5 or higher, PHP 5+

**Compatible up to:** 5.5.1

**Beta Version Disclaimer**

This plugin is still being tested.  Do not use it in production unless you are willing to accept the possibility that it could screw up your website while activated.  The alterations are non-destructive, so if it causes problems for you deactivating this plugin should restore your site to the way it was.

## Description

I got tired of making the _same_ responsive layout modifications to different WordPress themes over and over again and I wanted an easy way to create and modify a custom interface for mobile devices.  So I rolled all the stuff I was doing manually into a single plugin! ZappBar adds highly configurable mobile app style button bars to the top and bottom of any WordPress theme.  You can decide at what screen/browser widths they activate (so you can show them only for phones or tablets without altering your site for desktop users).  This plugin can also *try* to convert non-responsive themes to responsive layouts that auto-adjust for mobile devices and it can *try* to fix some mobile layout issues that bugged me about the WordPress admin backend too.

## Installation

### Using Admin Upload

1. Download the GitHub archive as a ZIP file.
2. Go to your _Dashboard > Plugins > Add New_ and press the "Upload Plugin" at the top of the page.
3. Browse to where you downloaded the ZIP file and select it.
4. Press the "Install Now" button.
5. On your _Dashboard > Plugins_ page activate "ZappBar"
6. Go to _Dashboard > Settings > Zappbar_ and configure it.

### Using FTP
  
2. Unzip it so you have a "zappbar-master" folder
3. FTP upload it into your WordPress blog’s _~/wp-content/plugins/_ folder.
4. Go to your _Dashboard > Plugins_ and activate “ZappBar”
5. Go to _Dashboard > Settings > ZappBar_ to configure it.


## Settings

### Site Layout

**Theme Layout:** 
* Theme is already responsive (so ZappBar shouldn’t try to “fix” it)
* Tweak theme to be responsive when ZappBars are displayed
* Retrofit to responsive theme even when ZappBars are not displayed (if possible)

The latter two options will _try_ to adjust a non-responsive theme so that it behaves like a responsive layout (auto-adjusting to different screen sizes).  This works best when the theme is using standard class names and IDs for the theme parts, though you can try using “Custom Settings” (see below) to tell ZappBar how to find those elements.

Applying "Tweaks" to a theme that is already responsive may or may not have any discernible effect, depending on the theme.  "Retrofit" is intended ONLY for themes that are not already responsive, and applying it to a theme that is already responsive will most likely really mess up the layout.

**Auto-detect Theme Width** If you are retrofitting a theme to be responsive this can _try_ to detect what the set width of the theme is so ZappBar will know where the breakpoint should be to make it responsive.

**Manual Theme Width** If you know the set width of a non-responsive theme or the automatic option didn't work you can manually tell ZappBar at what pixel width to set the breakpoint for responsiveness.

**Theme Sidebars:** Themes often have sidebars in them.  On narrow screen devices these can be a problem if the theme isn’t responsive.  If you told ZappBar to “Make theme responsive” now tell it what it should do with any sidebar containers it finds:

* Try to MOVE sidebar(s) below the main content on mobile devices
* Try to HIDE sidebar(s) but only from narrow mobile screens
* Try to HIDE sidebar(s) whenever ZappBars are being displayed

**Admin Mobile Layout:** All this does now is make the Admin Bar fixed in position and turns the sidebar menu into a slide-in panel.  It makes it a little nicer to use the WordPress backend on a phone.  All the previous layout fixes have been rolled into the regular ZappBar Admin Styles CSS file.

**WP Admin Bar:** check this box to disable the Admin Toolbar on the FRONT end of the website (this would be the bar across the top of a website when you are logged into it).

**ZappBars On Screen Size:** This is where you decide what size of screens will use the ZappBar user interface and which sizes won’t

* None (use this when you are setting up ZappBar)
* &lt; 736px wide (Phones) - this should work for most phones in portrait/landscape.
* &lt;1024px wide (Tablets & Phones) - up to “iPad” sized screen rendering, but many Android tablets will show ZappBars in portrait but not in landscape.
* &lt;1280px wide (Tablets & Phones) - should work for most Android tablets in both portrait/landscape.
* &lt;1440px wide (HD Tablets & Phones) - should work on Surface tablets
* &lt;1920px wide (Phones, Tablets, 720p HD Desktops) - should work on any screen that is UNDER “Full HD” resolution.
* All Screen Sizes - applies ZappBars regardless of screen size.

Note: if you've set Theme Layout to "Retrofit" but this is set to "None" it also will not retrofit the theme.  "None" completely disables ZappBar on the front-end of the site.

**Apply ZappBars On:** This is where you decide what DEVICES of the screen size(s) you set above will have ZappBars applied to them:

* All Devices By Screen Size - exactly what it sounds like.
* ONLY Mobile Devices by Screen Size - tries to exclude Desktops and Laptops.
* ONLY Mobile Devices & FORCE ZappBars - ignores screen size and acts like “All Screen Sizes” but ONLY on Mobile devices.
* Desktops by Screen Size & Force Mobile Devices to use Zappbars - the screen size(s) selected above will be used on Desktops and Laptops, but Mobile devices will have ZappBars applied regardless of the device screen size.

_“Mobile Only” uses device detection, which can be spoofed.  “Force” ignores screen size selection._

**When ZappBars are Included:** additional, optional, modifications to the theme layout:

* Hide Entire `<header>` Section - this is typically where the site masthead is and may also contain desktop navigation or custom widgets.
* Hide regular site navigation - if the desktop navigation can’t be hidden by hiding the `<header>` section, or if there is a mobile nav menu that is part of the theme but is redundant when ZappBars are in use.
* Convert Comment Section to App Panel - turns the entire comment thread, including the  comment form, below blog posts and page articles into a slide-in panel (requires linking a button to opening this panel or visitors won't be able to comment or read comments when ZappBars are in use).
* Convert Comment Form to App Panel - turns just the comment FORM into a slide-in panel, leaving the comment thread under the blog post or page article.  This is over-ridden if you check "Convert Comment Section" because the form will be included in the app panel automatically (you’ll probably also want to link a button to opening this panel or people won’t be able to comment when ZappBars are in use).
* Push _#page_ element over when App Panel is open - normally the panels slide in from the sides and overlay the page content.  Check this box to, instead, push the page content over when the panel slides into view.

**App Icon Image:** An optional icon or logo image that would be used as the favicon and/or the icon when bookmarked to the home screen of a mobile device (on devices that support it).

**Splash Image:** If you want your site to look even _more_ like an app on mobile devices you can add an image to use as the “Splash” when visitors come to your site on phones or tablets.  Whatever image you select is shown, centered and scaled, against whatever background color you’ve set for panel backgrounds.  This only displays ONCE at the start of a new user session (or it would be pretty annoying).

**Splash Image Fit:** If you included a splash image this adjusts how the image is scaled on that splash screen:

* Do NOT scale splash image, use the original size (may be cut off)
* Scale Splash Image to FIT if image is larger than screen space (pretty self-explanatory)
* Scale Splash Image to FILL all available screen space (may be cut off)

**Splash Timer:** select the amount of time to display the Splash screen (users can always clear it sooner by clicking on it).

**Splash Link:** If you are using the Splash screen to show an advertisement you can enter a URL target for the ad here.

**Custom Settings:** If you are using a theme that doesn’t use the typical class names or IDs for the parts of the layout you’ll need to tell ZappBar what they are:

* Header Target - typically whatever holds the site’s name/masthead.
* Navigation Target - the main navigation menu container
* Comment Target - element that contains blog post comment form
* Page Target - the main page content container
* Sidebar Targets - a comma-separated list of classes and/or IDs for sidebar elements (some may be part of the theme, others may have been added by plugins).
* Always Hide - comma-separated list of classes/IDs for anything _else_ you want hidden whenever the ZappBars are displayed.

Note that your custom targets will be ignored unless you've set "Theme Layout" to one of the retrofit settings.

**Theme &amp; Plugin Settings:** Allows you to customize settings or appearance of certain supported themes or other plugins while ZappBars are being displayed.

* Comic Navigation - (only shown if using a supported comics theme/plugin) you can choose to HIDE the default comic navigation when ZappBars are displayed.
* WooCommerce Site - (only shown if using the WooCommerce Plugin) allows you to convert some product page sections into mobile-friendly panels.

### Social Media

ZappBar has it’s own Social Media functions to make is easy for people to share your site on their social media feeds.  However, if you are already using another plugin on your site that adds social/sharing meta tags (such as Yoast SEO) that meta may be injected _before_ ZappBar’s in which case it will be read first by the social media site(s).

**Default Facebook Image:** If a post has no “Featured Image” this is the image that will be displayed as the thumbnail when somebody shares the link on Facebook (if left blank ZappBar will _not_ inject _any_ Facebook `<meta>` tags).

**Twitter ID:** Enter the @ Twitter ID associated with your blog (if any).  If you leave this blank then no Twitter `<meta>` tags will be injected.

**Phone Number:** If you intend to link a ZappBar button to the “Phone” action this is the phone number that will be inserted into the on-screen notice box.

**E-Mail Address:** If you link a ZappBar button to “E-Mail Contact” this is the e-mail address to which the message will be sent.  If you want people to e-mail the site administrator set your button action to “E-mail Admin” and it will use the address on your blog’s _Settings > General_ page.

**Social Media Panel:** Enable/Disable buttons for various social media websites:

* Facebook
* Twitter
* Reddit
* LinkedIn
* Pinterest
* RSS Feed
* Share via E-Mail

**Social Shortcode:** you can invoke this same list of social media links anywhere on your site, even when ZappBars are not being shown, by using the shortcode *_[zb-share]_* which accepts the following parameters:

* type=“text” - simple text-only links.
* type=“label” - (default) buttons with both a small icon and text label.
* type=“small” - 16x16 icons as button, spaced far enough apart to be clickable on mobile devices.
* type=“medium” - 24x25 icons as buttons
* type=“large” - 32x32 icons as buttons
* include=“twitter,linkedin…” - comma-separated list limiting which social media sites will be included.
* exclude=“facebook,pinterest…” - comma-separated list of which social media sites to exclude.

### ZappBar Colors

You can use either an external stylesheet or you can set individual item colors from within the plugin.  If you are using an external stylesheet it can be stored anywhere on the same server, just give the full URL to its location.  There is a `sample_styles.css` file in _zappbar/css/_ you can use as a starting point for creating your own.

_(the color options in the plugin are pretty self-explanatory, so they won’t be covered in this README)_

### ZappBar Panels

If you set a Custom Stylesheet on the *ZappBar Colors* tab everything on this tab will be ignored.  Otherwise you can define how the ZappBar styles will look and work here.

**Menu Panel:**  The “Menu” Panel is the replacement mobile-style navigation menu for your site when ZappBars are being displayed.  These settings determine how the menu items in that panel will be shown:

* Indent sub-items - moves sub-pages/sections over to make it obvious they are part of the parent item.
* Flatten List - no items (even if they’re subsections) will be indented.
* Show top-level Items Only - Parent items with subsections will only show the parent.  No sub-items will be shown in the menu.

**Sidebar Panels:** If you do not plan to link buttons to open/close the sidebar panels users will need some way to open/close them.  If this setting is enabled it will overlay your site, left and right, with two tabs which can be used to open/close the left/right panels.

**Panel Style Source:** Determines which styles are applied to ZappBar Panels:

* Use Bar Styles - Automatically uses the *ZappBar Colors* settings for the Panels too (which saves you having to repeat it all if you want them to look the same).  If selected all the visual settings below this in the tab section are ignored.
* Inherit from Theme - applies any relevant styles from the theme to your panels.  Can potentially save you time making panels look more integrated with your WordPress theme and will automatically apply new styles when you change themes.  If selected all the visual settings below this in the tab section are ignored.
* Use Styles Below for Panels - this is the only option that actually uses any custom styles you set on this tab.

_(the color and style options for the panels are also fairly self-explanatory so they won’t be covered in this README)_

### ZappBar Layout

This tab section shows you editable previews of what the ZappBars will look like on the front-end of your website.

**Button Layout:** Determines the position of the five buttons on the ZappBars:

* Evenly Spaced
* Centered and Outside
* Four Left, One Right
* Five Left
* One Left, Four Right
* Five Right

**ZappBar Search Box:** Normally the ZappBar “Search” field has a “Submit” button after it.  If you want a cleaner look you can hide the “Submit” button and have it automatically submit on hitting Enter/Return.

**Logo Icon:** (optional) Select an image to be your logo icon and then set one of the buttons to “logo” to display it (the image will be scaled to fit on the button).

**Button Label Text:** 

* Text on Button, No Tooltip - this is the default setting for how it has previously looked.
* Text on Button is also Tooltip - whatever text you enter as the button label will also be used for the tooltip text when you hover over the button.
* NO Text on button, Tooltip ONLY - for a cleaner, more modern look you can disable the text labels on the buttons.  You should still enter text, though, to be used as the Tooltip when the mouse hovers over the button AND you should select an icon that makes it clear what the button's function is or where it goes.

**Default Top/Bottom ZappBar:** Shows a preview of the default top bar.  Click on a button to change the icon and text.  Click on the “Show Button Actions” below it to “wire” a button to an action in the drop-down lists.

**Top/Bottom Archive ZappBar:** Determines whether the Default, Custom Archive, or NO top/bottom bar is used on Archive pages.

**Archive Top/Bottom ZappBar:** these bars will ONLY appear on an Archive page on your blog, and only if you enabled them under *Top Archive ZappBar* and/or *Bottom Archive ZappBar*

**Top/Bottom Blog ZappBar:** Determines whether the Default, Custom Blog, or NO top/bottom bar is used on single blog post pages.

**Blog Top/Bottom ZappBar:** these bars will ONLY appear on a single blog post, and only if you enabled them under *Top Blog ZappBar* and/or *Bottom Blog ZappBar*

If you are using the ComicPress theme, Comic Easel plugin, or Webcomic plugin more bar options are displayed for the custom comic post pages:

**Top/Bottom Comic ZappBar:** Choose whether to use the custom “comic” bars on comics pages, the Default ones, or no top/bottom bar.

**Comic Top/Bottom ZappBar:** optional bars you can have displayed only on comic post pages (if you are using one of the aforementioned comic themes/plugins).

If you are using the WooCommerce plugin you will also see options for custom e-commerce ZappBars:

**Top/Bottom WooCommerce ZappBar:** set whether to use custom, default, or no top/bottom bars on WooCommerce store-related pages.

**WooCommerce Top/Bottom Bar:** optional bars and buttons you can link to WooCommerce functions.

**WooCommerce Site:** tell ZappBar to further alter you e-commerce pages:

* Convert Woo Review to App Panel - moves the “Reviews” section out of the main page content and into a slide-in panel.
* Convert Woo Product Description to App Panel - moves the product description block into a slide-in panel.
* Convert Woo Additional Product Info to App Panel - moves “Additional Info” block into a slide-in panel.

## TIPS!

* If you set it to show ZappBars on all screen sizes/devices you may need to select *Site Layout > Theme Layout > Retrofit to responsive* even if the theme is already responsive.
* If you are using the WooCommerece plugin be aware the WooCart, WooAccount, etc., button actions ONLY work on WooCommerce-enabled pages.  
With themes that do not have built-in support for WooCommerce you can only use those actions on the pages the WooCommerce plugin creates. 
On other pages you can still have "Cart" and "Account" buttons, but the actions will have to link them to the actual PAGES and *not* the 
"Woo" functions.  Consequently the Cart icon will only show the number of items and total on WooCommerce-enabled pages, elsewhere it will 
simply be an icon with no dynamic elements.
* The "All Blog Posts" option in the button actions will show an ascending list of all blog posts in all categories.  If what you want is to show blog posts in descending order (newest to oldest) create a page named "Blog" and point the button action to that page instead of "All Blog Posts."
* The first three settings under "Site Layout" are very inter-related. If retro-fitting a theme you may have to try different combinations to find the right ones if the site, or parts of the site are not becoming responsive.  You may also need to enter custom target element IDs or classes if the theme uses custom ones.  ZappBar was designed for use with child themes based on the default WordPress, ComicPress, Inkblot, Webcomic, or WooCommerce themes.  So if you're using a child theme of some *other* base theme, you will likely need to customize the target settings to get retrofitting to work.
* Depending on what other plugins you have activated that ZappBar interacts with, you may need to go to *Dashboard > Settings > Permalinks* after activating/deactivating ZappBar.  ZappBar doesn't create any custom post types or rewrite rules but it does utilize those created by WooCommerce and any of the comic plugins.

## Notes

* Zappbar buttons are not functional when shown in Theme "Customize" interface.

## Changelog

Version 0.2.6
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

Version 0.2.5
* Updated browser sniffer for Edge Chromium
* Added option to turn full Comments Section into App Panel
* Added Auto-Updating directly from GitHub (no longer requires GitHub Updater plugin)
* Tested compatible up to PHP 7.1.31
* Google Plus removed from Social Media options.
* If ONLY the Comment Form is turned into an App Panel the Reply-To links no longer move the form but it still posts to correct place in comment thread.

Version 0.2.4
* Fixed Google Translate widget which didn't work unless using old ComicPress theme.

Version 0.2.3
* Removed Digg from Social Media options
* Removed StumbleUpon from Social Media options
* Fixed issue where inactive ZappBar buttons caused 404 Not Found errors for bots indexing site.  The button href placeholders were mistaken for links to non-existent pages.  They are now masked as in-page anchor links.
* Updated zappbar.js to find and activate buttons via the fake anchor links. 

Version 0.2.2
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

Version 0.2.1
* Fixed problem on search results pages where nothing is found where social media share panel tried to build but can't because $post doesn't exist.

Version 0.2

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
* Fixed undefined index social panel and settings
* Fixed missing default button bg opacity variable on activation
* Cleaned up sprintf in `class.settings-api.php`
* Added type for HTML5 input type="number" to `class.settings-api.php`
* Fixed inline styling of ZappBar menu items
* Fixed `#wpadminbar` and sidebar display issues with `sample_styles.css` file
* Merged in fixes to `html_inject.php` for ComicPress chapter navigation

Version 0.1

Initial public release.

## Developers

K.M. Hansen @kmhcreative - Lead Developer
http://www.kmhcreative.com

Philip Hofer @frumph - Contributor
http://frumph.net

## Resources

This uses my [Icon Picker](https://github.com/kmhcreative/icon-picker) (which is based on [Dashicons Picker](https://github.com/bradvin/dashicons-picker) by Brad Vincent) to get/set the icons on the ZappBars.

[ComicPress 2.9 Theme](http://comicpress.org/)

[ComicPress 4.x Theme](https://github.com/Frumph/comicpress)

[Webcomic Plugin](https://wordpress.org/plugins/webcomic/)

[Manga+Press Plugin](https://wordpress.org/plugins/mangapress/)

[WooCommerce Plugin](https://wordpress.org/plugins/woocommerce/)

