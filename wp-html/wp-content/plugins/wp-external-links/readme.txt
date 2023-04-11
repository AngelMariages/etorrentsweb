=== External Links - nofollow, noopener & new window ===
Contributors: WebFactory
Tags: new window, new tab, external links, nofollow, noopener, ugc, sponsored, follow, dofollow, noreferrer, internal links, links, link, internal link, external link, link scanner, link checker
Requires at least: 4.2
Tested up to: 6.2
Requires PHP: 7.2
Stable tag: 2.58
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Internal links & external links manager: open in new window or tab, control nofollow, ugc, sponsored & noopener. SEO friendly.

== Description ==

**Manage all external & internal links on your site**. Control icons, nofollow, noopener, ugc (User Generated Content), sponsored and if links open in new window or new tab.

<a href="https://getwplinks.com/">WP Links</a> plugin was completely rebuilt in v2 and has lots of new features, like noopener, ugc and sponsored values for rel; font icons, internal links options and full WPMU support.

= Link Scanner - PRO feature =
Check every single link on your site! See if it's broken or not, if it's redirected, what's the target and rel attribute and what page exactly it's linking to. This feature is a part of the <a href="https://getwplinks.com/">WP Links PRO</a> plugin.

= Features =
* Manage external and internal links
* Open links in new window or tab
* Add follow or nofollow (for SEO)
* Add noopener and noreferrer (for security)
* Add ugc (User Generated Content) and sponsored values to rel (<a href="https://webmasters.googleblog.com/2019/09/evolving-nofollow-new-ways-to-identify.html">Google announcement</a>)
* Add link icons (FontAwesome and Dashicons)
* Set other attributes like title and CSS classes
* Scan complete page (or just posts, comments, widgets)
* SEO friendly
* Link Scanner - check all links on your site - PRO feature
* Exit Confirmation - protect visitors and traffic when external links are clicked - PRO feature
* Link Rules - create advanced link rules for chosen link groups - PRO feature

= And more... =
* Network Settings (WPMU support)
* Use template tag to apply plugin settings on specific contents
* Set data-attribute to change how individual links will be treated
* Use built-in actions and filters to implement your specific needs

= Easy to use =
After activating you can set all options for external and internal links on the plugins admin page.

= On the fly =
The plugin filters the output and changes the links on the fly. The real contents (posts, pages, widget etcetera) will not be changed in the database.
When deactivating the plugin, all contents will be the same as it was before.

= GDPR compatibility =
We are not lawyers. Please do not take any of the following as legal advice.
WP External Links does not track, collect or process any user data. Nothing is logged or pushed to any 3rd parties. We also don't use any 3rd party services or CDNs. Based on that, we feel it's GDPR compatible, but again, please, don't take this as legal advice.


**Like the plugin?** [Rate it](http://wordpress.org/support/view/plugin-reviews/wp-external-links) to support the development.

If you're having **problems with SSL or HTTPS** try our free <a href="https://wordpress.org/plugins/wp-force-ssl/">WP Force SSL</a> plugin. It's a great way to enable SSL and fix SSL problems.

== Installation ==

1. Go to **Plugins** in the Admin menu
1. Click on the button **Add new**
1. Search for **WP External Links** and click **Install Now**
1. Click on the **Activate plugin** link


== Frequently Asked Questions ==

= I want certain posts or pages to be ignored by the plugin. How? =

Just use the option "Skip pages or posts" under the tab "Exceptions".

For a more custom approach use the action `wpel_apply_settings`:
`add_action( 'wpel_apply_settings', function () {
    global $post;
    $ignored_post_ids = array( 1, 2, 4 );

    if ( in_array( $post->ID, $ignored_post_ids ) ) {
        return false;
    }

    return true;
}, 10 );`

Using this filter you can ignore any request, like certain category, archive etcetera.

= I want specific links to be ignored by the plugin. How? =

There's an option for ignoring links containing a certain class (under tab "Exceptions").

For a more flexible check on ignoring links you could use the filter `wpel_before_apply_link`:
`add_action( 'wpel_before_apply_link', function ( $link ) {
    // ignore links with class "some-cls"
    if ( $link->has_attr_value( 'class', 'some-cls' ) ) {
        $link->set_ignore();
    }
}, 10 );`


= How to create a redirect for external links? (f.e. affiliate links) =

Create redirect by using the `wpel_link` action. Add some code to functions.php of your theme, like:

`add_action( 'wpel_link', function ( $link ) {
    // check if link is an external links
    if ( $link->is_external() ) {
        // get current url
        $url = $link->get_attr( 'href' );

        // set redirect url
        $redirect_url = '//somedom.com?url='. urlencode( $url );
        $link->set_attr( 'href', $redirect_url );
    }
}, 10, 1 );`

= How to open external links in a new popup window? =

By adding this JavaScript code to your site:

`jQuery(function ($) {
    $('a[data-wpel-link="external"]').click(function (e) {
        // open link in popup window
        window.open($(this).attr('href'), '_blank', 'width=800, height=600');

        // stop default and other behaviour
        e.preventDefault();
        e.stopImmediatePropagation();
    });
});`

See more information on the [window.open() method](http://www.w3schools.com/jsref/met_win_open.asp).

= How to add an confirm (or alert) when opening external links? =

Add this JavaScript code to your site:

`jQuery(function ($) {
    $('a[data-wpel-link="external"]').click(function (e) {
        if (!confirm('Are you sure you want to open this link?')) {
            // cancelled
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });
});`

= How to open PDF files in a new window? =

Use some JavaScript code for opening PDF files in a new window:

`jQuery(function ($) {
    $('a[href$=".pdf"]').prop('target', '_blank');
});`

= How to set another icon for secure sites (using https)? =

Use some CSS style to change the icon for secure sites using https:

`a[href^="https"] .wpel-icon:before {
  content: "\f023" !important;
}`

The code `\f023` refers to a dashicon or font awesome icon.

= I am a plugin developer and my plugin conflicts with WPEL. How can I solve the problem? =

If your plugin contains links it might be filtered by the WPEL plugin as well, causing a conflict.
Here are some suggestions on solving the problem:

1. Add `data-wpel-link="ignore"` to links that need to be ignored by WPEL plugin
1. Use `wpel_before_apply_link`-action to ignore your links (f.e. containing certain class or data-attribute)
1. Use `wpel_apply_settings`-filter to ignore complete post, pages, categories etc


== Screenshots ==

1. Admin Settings Page
2. Link Checker / Link Tester
3. Link Icons


== Documentation ==

After activating you can set all options for external and internal links.

= Data attribute "data-wpel-link" =

Links being processed by this plugin will also contain the data-attribute `data-wpel-link`.
The plugin could set the value to `external`, `internal` or `exclude`, meaning how the
link was processed.

You can also set the data-attribute yourself. This way you can force how the plugin will process
certain links.

When you add the value `ignore`, the link will be completely ignored by the plugin:

`<a href="http://somedomain.com" data-wpel-link="ignore">Go to somedomain</a>`


= Action "wpel_link" =

Use this action to change the link object after all plugin settings have been applied.

`add_action( 'wpel_link', ( $link_object ) {
    if ( $link_object->is_external() ) {
        // get current url
        $url = $link_object->getAttribute( 'href' );

        // set redirect url
        $redirect_url = '//somedom.com?url='. urlencode( $url );
        $link_object->setAttribute( 'href', $redirect_url );
    }
}, 10, 1 );`

The link object is an instance of `WPEL_Link` class.

= Action hook "wpel_before_apply_link" =

Use this action to change the link object before the plugin settings will be applied on the link.
You can use this filter f.e. to ignore individual links from being processed. Or change dynamically how
they will be treated by this plugin.

`add_action( 'wpel_before_apply_link', function ( $link ) {
    // ignore links with class "some-cls"
    if ( $link->has_attr_value( 'class', 'some-cls' ) ) {
        $link->set_ignore();
    }

    // mark and treat links with class "ext-cls" as external link
    if ( $link->has_attr_value( 'class', 'ext-cls' ) ) {
        $link->set_external();
    }
}, 10 );`

= Filter hook "wpel_apply_settings" =

When filter returns false the plugin settings will not be applied. Can be used when f.e. certain posts or pages should be ignored by this plugin.

`add_filter( 'wpel_apply_settings', '__return_false' );`


See [FAQ](https://wordpress.org/plugins/wp-external-links/faq/) for more info.


== Changelog ==

= 2.58 =
 * 2023-03-08
 * security fix
 
= 2.57 =
 * 2022-12-27
 * added double-check on all template include paths
 
= 2.56 =
 * 2022-11-20
 * security fixes
 * removed all external CDNs
 * fixed translation issues

= 2.55 =
 * 2022-07-09
 * fixed current_screen() bug
 * fixed "icon_type" cannot be found bug
 * introduction of the PRO version
 * removed link scanner beta

= 2.51 =
 * 2022-01-28
 * fixed icons for excluded external links
 * fixed FontAwesome conflict

= 2.50 =
 * 2021-07-09
 * completely new GUI
 * link scanner / link analyzer SaaS beta

= 2.48 =
 * 2021-01-30
 * added flyout menu
 * removed promo for WP 301 Redirects PRO

= 2.47 =
 * 2020-10-05
 * added settings link to plugins table
 * added promo for WP 301 Redirects PRO

= 2.46 =
 * 2020-05-09
 * fixed "unable to save post" when using Gutenberg
 * sorry for two updated in two days :(

= 2.45 =
 * 2020-05-08
 * fixed compatibility issue with Site Kit by Google

= 2.43 =
 * 2019-12-27
 * improved link detection regex

= 2.42 =
 * 2019-12-18
 * fixed various undefined variable notices
 * fixed an issue when there’s no href attribute set in an A element

= 2.40 =
 * 2019-11-20
 * no longer loads CSS and FontAwesome globally on admin pages
 * added support for "ugc" (User Generated Content) and "sponsored" values in rel

= 2.35 =
 * 2019-09-12
 * FontAwesome updated to 4.7 with 41 new icons
 * bug fix: ship anchor URLs (#anchor)
 * bug fix: don't match domain if the name is contained in the URL

= 2.32 =
 * 2019-07-09
 * security fixes

= 2.3 =
 * 2019-06-14
 * bug fixes
 * 40,000 installations hit on 2018-03-13
 * for older changelog entries please visit https://getwplinks.com/old-changelog.txt
