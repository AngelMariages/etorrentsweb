<div class="wrap">
    <div class="icon32" id="icon-tools"><br></div>
    <h2><?php _e('ZOOM Framework Update', 'wpzoom'); ?></h2>

        <?php
        $isUpdated = false;

        $remoteVersion = WPZOOM_Framework_Updater::get_remote_version();
        $localVersion = WPZOOM_Framework_Updater::get_local_version();

        if (preg_match('/[0-9]*\.?[0-9]+/', $remoteVersion)) {

            if (version_compare($localVersion, $remoteVersion) < 0) {
                $isUpdated = true;
            }

        } else {
            echo '<p>' . $remoteVersion . '</p>';
        }
        ?>

        <?php if ($isUpdated) : ?>

            <div style="border: 1px solid #E6DB55;background-color: #FFFBCC;color: #424242;padding: 20px;margin-top: 20px;border-radius:4px;">
                <?php _e('A new update for the framework of your <strong>WPZOOM</strong> theme is available!', 'wpzoom'); ?>
                <p><?php _e('<strong>NOTICE:</strong> <em>Updating the framework will not affect any of the changes or customization you have made to the theme or to your website. For more information visit this <a href="https://www.wpzoom.com/docs/using-the-zoom-framework-automatic-updates/" target="_blank">tutorial</a></em>.', 'wpzoom'); ?></p>

                <form method="post" id="wpzoom-update">
                    <input type="hidden" name="wpzoom-update-do" value="update" />
                    <?php printf(
                        __('%sUpdate Framework Automatically%s ', 'wpzoom'),
                        '<input type="submit" class="button button-primary" value="',
                        '" />'
                    ); ?>
                </form>
            </div>

            <?php if (method_exists('WPZOOM_Framework_Updater', 'get_changelog')) : ?>
            <h3>Changelog</h3>
            <div style="height: 400px; overflow: scroll; border: 1px solid #ccc; border-radius:3px; padding: 0 10px; background: #F8F8F8; font-size: 12px;">
                <?php
                $start = false;
                $changelog = WPZOOM_Framework_Updater::get_changelog();
                $changelog = explode("\n", $changelog);
                foreach ($changelog as $line) {
                    if (preg_match("/v ((?:\d+(?!\.\*)\.)+)(\d+)?(\.\*)?/i", $line)) {
                        $start = true;
                        echo '<h4>' . $line . '</h4>';
                    } elseif($start && trim($line)) {
                        echo '<pre>' . $line . '</pre>';
                    }
                }
                ?>
            </div>
            <?php endif; ?>
        <?php else : ?>
            <p><?php printf(__('&rarr; <strong>You are using the latest framework version:</strong> %s', 'wpzoom'), $localVersion); ?></p>
            <?php option::delete('framework_status'); ?>
        <?php endif; ?>
</div><!-- end .wrap -->
