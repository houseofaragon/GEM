This WordPress theme is comprised of two parts:


(1) The PHP code is licensed under the GPL license as is WordPress itself.  You will find a copy of the license text in the same directory as this text file. Or you can read it here:

http://codex.wordpress.org/GPL



(2) All other parts of the theme including, but not limited to the CSS code, images, and design are licensed according to the license purchased. Read about licensing details here:

http://themeforest.net/licenses/regular_extended






=== Migration Guide - v.2.6.4 -> 3.0 ===

Muzak has been updated to work properly with WooCommerce >= v2.0, which breaks compatibility with previous WooCommerce versions. Please read carefully the following, before updating your theme.

WooCommerce Installations
-------------------------
Here are the hard facts:
* WooCommerce >= v2.0 requires WordPress >= v3.5
* Muzak >= v2.0 requires WooCommerce >=2.0 (or no WooCommerce at all)

Therefore, you need to update your WordPress installation and the Muzak theme and the WooCommerce plugin.

In order to update your installation without issues, the following steps must be undertaken in order:

1) Keep a full backup of your website, both files and database. Make sure you know how to restore your website from the backup.
2) Disable the WooCommerce plugin.
3) Update WordPress to the latest version.
4) Update Muzak to the latest version.
5) Update WooCommerce plugin to the latest version. You will probably want to follow these instructions: http://www.woothemes.com/2013/02/preparing-your-website-for-woocommerce-2-0/
6) Go to Settings -> Permalinks, and press "Save Changes" once you update WooCommerce.
7) You may need to re-select your WooCommerce pages from WooCommerce -> Settings -> Pages panel.

Ideally, you will test the above procedure on an development server, and not directly to the live website.


All Installations (regardless of WooCommerce)
---------------------------------------------
There have been some changes that will make the use of the theme easier. Although you might have set-up your theme exactly the way you want it, you might want to consider the following changes, or keep in mind for future reference:

* There is a new audio player, soundManager2, that is bundled with the theme. It is used by default, unless you have already installed and activated JWPlayer. It is worth trying out the new player as it's more lightweight, and there are no extra user interface elements except the play button.

* Track listings in Discographies are now easier to create, without the use of a shortcode. Below the main content editor, inside the Discographies Settings, you can now find a new "Track Details" section that allows you to add as many tracks as you want, by pressing the "Add Track" button. The available fields are the same as the old [tracklisting] [track][/track] [/tracklisting] shortcodes.

* Discographies' tracks can now support lyrics.

* Discographies' tracks also support SoundCloud. You need to enter the SoundCloud URL into the Play URL. You must also have installed the SoundCloude Shortcode plugin, available from http://wordpress.org/plugins/soundcloud-shortcode/

* The new track listings mentioned above, can be placed anywhere on the discography content, just by using the [tracklisting] without any further information of options.

* As mentioned, a plain [tracklisting] shortcode by default shows the associated tracks to the current discography item.
You may also display the track listing of any discography item in any other post/page or widget (that supports shortcodes) by passing the ID or slug parameter to the shortcode. E.g. [tracklisting id="25"] or [tracklisting slug="the-division-bell"]

You can also selectively display tracks, by passing their track number (counting from 1), separated by a comma, like this [tracklisting tracks="2,5,8"] and can limit the total number of tracks displayed like [tracklisting limit="3"]

Of course, you can mix and match the parameters, so the following is totally valid: [tracklisting slug="the-division-bell" tracks="2,5,8" limit="2"]

The older syntax, [tracklisting][track][/track][/tracklisting] is still supported but not prefered.

* There is a new widget, -= CI Album Tracklisting =- that allows you to display the tracks of any discography item, in any widget area. This widget requires that the discography item has its tracks entered through the new "Track Details" section, as mentioned above.
