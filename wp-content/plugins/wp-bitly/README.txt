=== Plugin Name ===
Contributors: delayedinsanity, chipbennett
Tags: shortlink, short, link, bitly, url, shortener, social, media, share, twitter, facebook
Requires at least: 3.9.1
Tested up to: 3.9.1
Stable tag: 2.2.9

Use Bitly generated shortlinks for all your WordPress posts and pages, custom post types or attachments.


== Description ==

WP Bitly easily integrates with your WordPress site to replace the internally generated shortlinks with Bitly generated short urls. Read about WP Bitly on [WPBeginner](http://www.wpbeginner.com/blueprint/wp-bitly/).

Provide the plugin with your Bitly authorization token, tell it which post types you'd like to generate shortlinks for, and WP Bitly will handle the rest.

Shortlinks are a great way to quickly share posts on social media like Twitter, Instagram and Facebook. Just finished writing an amazing post and want to share that post with your friend? It's a lot easier to text message a shortlink than the entire address.

WP Bitly also provides some insights (via a metabox on your edit post screen) as to how your link is being shared, and where it's being shared.

= Coming Soon =

* More feedback from Bitly on how your link is generating leads
* Feature Requests are welcome via the [Support Forum](http://wordpress.org/support/plugin/wp-bitly)


== Installation ==

= Upgrading =

Older versions of WP Bitly used a beta API provided by Bitly that required a username and API key. The more recent versions of WP Bitly use the V3 Bitly API which only requires a single OAuth token to generate short links.

You will need to upgrade from the WordPress dashboard, and navigate to the *Dashboard >> Settings >> Writing* page to add your new OAuth Token if you're coming from a version prior to 2.0.

= Add New Plugin =

1. From the *Dashboard* navigate to *Plugins >> Add New*
2. Enter "WP Bitly" in the search field
3. Select *Install Now*, click *OK* and finally *Activate Plugin*
4. This will return you to the WordPress Plugins page. Find WP Bitly in the list and click the *Settings* link to configure.
5. Enter your OAuth token, and that's all! You're done!


== Frequently Asked Questions ==

= After installation, do I need to update all my posts for short links to be created? =

No. The first time a shortlink is requested for a particular post, WP Bitly will automatically generate one.

= What happens if I change a posts permalink? =

WP Bitly will verify the shortlink when it's requested and update as necessary all on its own.

= Can I include the shortlink directly in a post? =

Sure can! Just use our handy dandy shortcode `[wpbitly]` and shazam! The shortcode accepts all the same arguments as the_shortlink(). You can also set "post_id" directly if you wish.

= How do I include a shortlink using PHP? =

`<?php wpbitly_shortlink(); // shortcode shweetness. ?>`

*(You don't have to include the php comment, but you can if you want.)*


== Changelog ==

= 2.2.9 =
* Disabled previously added filters until they can be made user configurable on the dashboard
= 2.2.8 =
* Added `post_link` and `post_type_link` filters to provide shortlinks to plugins that are calling `get_permalink` instead of `wp_get_shortlink`
= 2.2.6 =
* Fixed bug where shortlinks were generated for any post type regardless of setting
* Added `save_post` back, further testing needed
= 2.2.5 =
* Added the ability to turn debugging on or off, to aid in tracking down issues
* Updated to WordPress coding standards
* Removed `wpbitly_generate_shortlink()` from `save_post`, as filtering `pre_get_shortlink` seems to be adequate
* Temporarily removed admin bar shortlink button (sorry! it's quirky)
= 2.2.3 =
* Replaced internal use of cURL with wp_remote_get
* Fixed a bug where the OAuth token wouldn't update
= 2.0 =
* Updated for WordPress 3.8.1
* Updated Bitly API to V3
* Added WP Bitly to GitHub at https://github.com/mwaterous/wp-bitly
= 1.0.1 =
* Fixed bad settings page link in plugin row meta on Manage Plugins page
= 1.0 =
* Updated for WordPress 3.5
* Removed all support for legacy backwards compatibility
* Updated Settings API implementation
* Moved settings from custom settings page to Settings -> Writing
* Enabled shortlink generation for scheduled (future) posts
* Added I18n support.
= 0.2.6 =
* Added support for automatic generation of shortlinks when posts are viewed.
= 0.2.5 =
* Added support for WordPress 3.0 shortlink API
* Added support for custom post types.
= 0.1.5 =
* Bugfix
= 0.1.4 =
* Bugfix
= 0.1.0 =
* Initial release of WP Bitly
