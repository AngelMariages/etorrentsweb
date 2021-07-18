<?php
/**
 * Tab Support
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 *
 * @var array $vars
 *      @option array  "tabs"
 */
?>

<?php
if(WPEL_LinkHero::is_localhost()){
    ?>
    <div id="wpel-checker-consent">
    <div class="notice-box-error">
      The <b>Link Checking &amp; Analysis</b> is not available for websites running on localhost or on non-publicly accessible hosts.<br>This service is a SaaS and needs to be able to access your site in order to analyze links on it.
    </div>

    <p><b>We're in beta 🔥</b><br>
    Before you continue reading, please note that this <b>service is in beta</b>. So all kind of problems are possible.<br>
    But fear not! If anything is not working here's our <a href="mailto:support+wpel@webfactoryltd.com?subject=<?php echo rawurlencode(htmlspecialchars_decode('WPEL problems on ' . home_url())); ?>">direct support email</a> - ping us, please, so we can fix things.<br>
    The service doesn't cost anything but the number of pages you can scan is limited while it's in beta.</p>

    <p><b>What data am I sharing with you?</b><br>
    Absolutely no data is taken directly from WP and shared with our service! No emails, no post lists, no post content, no links - nothing! We'll access your site just like any other visitor does and check links on every post. We'll only have access to thinks that are publically available - nothing else. Obviously, if you don't agree with this please don't use the service.</p>

    <p><b>What are the benefits of using the Link Scanner?</b><br>
    You can look at it as a broken link checker, but it's a lot more.<br>
    After grabbing your sitemap the service will visit every page, post, product, and oder content listed on the sitemap and then check each link in that content. For each link it checks if it's alive, if it's redirect, what are its target and rel attributes. That way you can quickly check all the links on all of your pages in a matter of minutes and modify them.<br>
    This is also a great way to check if the settings you applied in the plugin are working and properly applied on all links.</p>

    <p><b>Will the Scanner slow down my site?</b><br>
    It's designed not to. We carefully pace out all requests so that we don't create too much traffic/load on your site in a short period of time. While the scanner is not running it's not using any resources at all.</p>

    <p><b>How long does a scan take?</b><br>
    For a site with an average number of links - about two minutes. However that depends on the speed of your site, the speed of the sites you link to, and the total number of links on your site we need to check.</p>

    </div>
    <?php
} else {
    $linkhero = get_option('wpel-linkhero', array('checker' => array(), 'enabled' => false));
    if(!isset($linkhero['enabled']) || $linkhero['enabled'] != true){ ?>
    <div id="wpel-checker-consent">
      <div class="notice-box-info">
        <p>The Link Checker service uses a 3rd party SaaS owned and operated by <a href="https://www.webfactoryltd.com/" target="_blank">WebFactory Ltd</a> to scan/crawl your website's pages and check/analyze all links. <b>Absolutely no private information from the website is shared or transferred to WebFactory.</b> Only publicly accessible content will be checked. Posts, pages and other content that's not published will not be analyzed.<br><br>
      More details are available below. If you're not sure if you should anaylze links with this service or anything's not clear please contact us.</p>
    </div>
    </div>
    <?php } ?>

    <a href="#" class="button button-primary check-links">Check &amp; Analyze all site's Links</a>
    <a href="#" class="button button-primary check-links" style="background: #F00; border: 1px solid #e30f0f; float:right;" data-force="true">Clear Results &amp; Cache</a>

    <div class="lh-search-wrapper">
        <input placeholder="Filter pages" type="text" id="lh-search" value="" />
    </div>

    <div id="lh-progress-bar-wrapper">
        <div id="lh-progress-bar"></div>
    </div>
    <table id="lh_results"></table>


    <div id="lh_pro">
        <div id="lh_pro_message">There are another <span id="lh_pro_count">0</span> pages that were not scanned because we're in beta.<br>
      Want to scan your entire site and check out other features? Leave your email and we'll get in touch soon 🚀</div>
        <div id="lh_pro_subscribe">
            <?php
            $subscribed = get_option('wpel-linkhero-subscribed');
            if($subscribed){
                ?>
                <div id="lh_subscribe_message" class="lh-subscribe-good" style="display:block;"><b>Thank you!</b> We'll be in touch soon!</div>
                <?php
            } else {
                ?>
                <input type="text" id="lh_subscribe_email" placeholder="Your very best email address" value="" />
                <div id="lh_subscribe_button" class="button button-primary">Subscribe</div>
                <div id="lh_subscribe_button_loader"></div>
                <div id="lh_subscribe_message" class="lh-subscribe-good"></div>
                <?php
            }
            ?>
        </div>

    </div>

    <div id="lh_details">
        <div class="lh-close"></div>
        <div id="lh_details_title"></div>
        <div id="lh_page_details_wrapper">
            <table id="lh_page_details" style="width:100%">
                <thead>
                    <th><span class="dashicons dashicons-admin-links"></span></th>
                    <th>Anchor text/url</th>
                    <th>Page title</th>
                    <th>Link type</th>
                    <th>Rel Attributes</th>
                    <th>Target</th>
                    <th>Redirected</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div id="lh_support">
        <p>If you encounter any issues using the Link Checker please contact our <a href="mailto:support+wpel@webfactoryltd.com?subject=<?php echo rawurlencode(htmlspecialchars_decode('WPEL problems on ' . home_url())); ?>">Support team</a>.</p>

        <p><b>We're in beta 🔥</b><br>
    Before you continue reading, please note that this <b>service is in beta</b>. So all kind of problems are possible.<br>
    But fear not! If anything is not working here's our <a href="mailto:support+wpel@webfactoryltd.com?subject=<?php echo rawurlencode(htmlspecialchars_decode('WPEL problems on ' . home_url())); ?>">direct support email</a> - ping us, please, so we can fix things.<br>
    The service doesn't cost anything but the number of pages you can scan is limited while it's in beta.</p>

    <p><b>What data am I sharing with you?</b><br>
    Absolutely no data is taken directly from WP and shared with our service! No emails, no post lists, no post content, no links - nothing! We'll access your site just like any other visitor does and check links on every post. We'll only have access to thinks that are publically available - nothing else. Obviously, if you don't agree with this please don't use the service.</p>

    <p><b>What are the benefits of using the Link Scanner?</b><br>
    You can look at it as a broken link checker, but it's a lot more.<br>
    After grabbing your sitemap the service will visit every page, post, product, and oder content listed on the sitemap and then check each link in that content. For each link it checks if it's alive, if it's redirect, what are its target and rel attributes. That way you can quickly check all the links on all of your pages in a matter of minutes and modify them.<br>
    This is also a great way to check if the settings you applied in the plugin are working and properly applied on all links.</p>

    <p><b>Will the Scanner slow down my site?</b><br>
    It's designed not to. We carefully pace out all requests so that we don't create too much traffic/load on your site in a short period of time. While the scanner is not running it's not using any resources at all.</p>

    <p><b>How long does a scan take?</b><br>
    For a site with an average number of links - about two minutes. However that depends on the speed of your site, the speed of the sites you link to, and the total number of links on your site we need to check.</p>
    </div>
    <?php
}
?>
