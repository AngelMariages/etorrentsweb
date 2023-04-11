<?php
/**
 * Tab Link Scanner
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

<div id="wpel-link-rules">
    <div class="notice-box-info">
      <b>Link Checker (a PRO feature) scans your website's pages &amp; analyzes all links.</b> It not only checks if links are valid, but also checks their details such as REL attributes (nofollow, dofollow, noopener, sponsored, ...) and checks if they are pointing to bad domains. Linking to domains that contain pornography, illegal downloads or that contain malware can hurt your site's SEO. Link Scanner will help you get rid of those links in minutes, regardless of your site size. <a href="#" class="open-pro-dialog" data-pro-feature="link-checker-banner">Get PRO now</a> to use the Link Checker.
    </div>

    <p><a href="#" class="open-pro-dialog" data-pro-feature="link-checker"><img style="max-width: 100%;" src="<?php echo esc_url(plugins_url('/public/images/link-checker.png', WPEL_Plugin::get_plugin_file())); ?>" alt="Link checker" title="Link checker"></a></p>

    <p><b>Will the Scanner help my SEO score?</b><br>
    The Scanner is a reporting tool, so it doesn't modify any links on its own. However, it'll show you a list of broken and bad links that you have on your site. By removing those you can improve your SEO score.</p>

    <p><b>Does the Scanner use my server's resources?</b><br>
    No. It's a SaaS so the majority of work is done on our servers. The resources used are the same as if a visitor opened every page on your site.</p>

    <p><b>What are the benefits of using the Link Scanner?</b><br>
    You can look at it as a broken link checker, but it's a lot more.<br>
    After grabbing your sitemap the scanner will visit every page, post, product, and other content listed on the sitemap and then check each link in that content. For each link it checks if it's alive, if it's redirect, what are its target and rel attributes as well as the site it links too. That way you can quickly check all the links on all of your pages and modify them if necessary.<br>
    This is also a great way to check if the settings you applied in the plugin are working and properly applied on all links.</p>

    <p><b>Will the Scanner slow down my site?</b><br>
    It's designed not to. We carefully pace out all requests so that we don't create too much traffic/load on your site in a short period of time. While the scanner is not running it's not using any resources at all.</p>

    <p><b>How long does a scan take?</b><br>
    For a site with an average number of links - about two minutes. However that depends on the speed of your site, the speed of the sites you link to, and the total number of links on your site Link Checker has to check.</p>

    <p><b>Wasn't this tool free before?</b><br>
    It was free for a limited number of pages while it was in public beta.</p>

</div>
