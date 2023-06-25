<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$option_names = array(
    'alfic_post_types',
    'alfic_column_label',
    'alfic_max_width',
    // you can add more option names here...
);

foreach ($option_names as $option_name) {
    delete_option($option_name);
    // for site options in Multisite
    delete_site_option($option_name);
}
