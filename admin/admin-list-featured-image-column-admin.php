<?php
// If this file is called directly, abort
if (!defined('WPINC')) {
    die;
}

// Make sure featured images are enabled
add_action('after_setup_theme', 'alfic_enable_thumbnails');
function alfic_enable_thumbnails()
{
    // Check if theme supports post-thumbnails already
    if (current_theme_supports('post-thumbnails')) {
        return;
    }
    add_theme_support('post-thumbnails');
}

// Add settings page to the admin menu
add_action('admin_menu', 'alfic_add_menu');
function alfic_add_menu()
{
    add_options_page(
        esc_html__('Admin List Featured Image Column', 'admin-list-featured-image-column'),
        esc_html__('Admin List Featured Image Column', 'admin-list-featured-image-column'),
        'manage_options',
        'admin-list-featured-image-column',
        'alfic_settings_page'
    );
}

// Register settings
add_action('admin_init', 'alfic_register_settings');
function alfic_register_settings()
{
    register_setting('admin_list_featured_image_column', 'alfic_post_types', array('sanitize_callback' => 'alfic_sanitize_post_types'));
    register_setting('admin_list_featured_image_column', 'alfic_column_label', array('sanitize_callback' => 'sanitize_text_field'));
    register_setting('admin_list_featured_image_column', 'alfic_max_width', array('sanitize_callback' => 'alfic_sanitize_max_width'));
}

// Sanitize post types
function alfic_sanitize_post_types($post_types)
{
    if (!is_array($post_types)) {
        $post_types = array();
    }
    return $post_types;
}

// Sanitize max width
function alfic_sanitize_max_width($max_width)
{
    $max_width = absint($max_width);
    return $max_width;
}

// Settings page callback function
function alfic_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
?>
    <div class="wrap">
        <h1><?php esc_html_e('Admin List Featured Image Column Settings', 'admin_list_featured_image_column'); ?></h1>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <?php settings_fields('admin_list_featured_image_column'); ?>
            <?php do_settings_sections('admin_list_featured_image_column'); ?>
            <?php wp_nonce_field('alfic_save_settings', 'alfic_settings_nonce'); ?>
            <input type="hidden" name="action" value="alfic_save_settings">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Select Post Types', 'admin-list-featured-image-column'); ?></th>
                    <td>
                        <p><?php esc_html_e('Select the post types where you want to display the thumbnail column.', 'admin-list-featured-image-column'); ?></p>

                        <h4><?php esc_html_e('Built-in Post Types', 'admin-list-featured-image-column'); ?></h4>
                        <?php
                        $built_in_post_types = array('post', 'page');
                        $selected_post_types = get_option('alfic_post_types', array());

                        foreach ($built_in_post_types as $post_type) {
                            $checked = in_array($post_type, (array)$selected_post_types, true) ? 'checked' : '';
                            $post_type_object = get_post_type_object($post_type);
                            echo '<label><input type="checkbox" name="alfic_post_types[]" value="' . esc_attr($post_type) . '" ' . $checked . '> ' . esc_html($post_type_object->label) . '</label><br>';
                        }
                        ?>

                        <h4><?php esc_html_e('Custom Post Types', 'admin-list-featured-image-column'); ?></h4>
                        <?php
                        $custom_post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');

                        $has_custom_post_types = false; // Flag to check if custom post types exist

                        foreach ($custom_post_types as $post_type) {
                            $checked = in_array($post_type->name, (array)$selected_post_types, true) ? 'checked' : '';
                            $post_type_object = get_post_type_object($post_type->name);

                            if (!$has_custom_post_types) {
                                $has_custom_post_types = true;
                                echo '<hr>';
                            }

                            echo '<label><input type="checkbox" name="alfic_post_types[]" value="' . esc_attr($post_type->name) . '" ' . $checked . '> ' . esc_html($post_type_object->label) . '</label><br>';
                        }

                        if (!$has_custom_post_types) {
                            echo '<p>' . esc_html__('No custom post types found.', 'admin-list-featured-image-column') . '</p>';
                        }
                        ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Column Label', 'admin-list-featured-image-column'); ?></th>
                    <td>
                        <p><?php esc_html_e('Enter the label for the thumbnail column.', 'admin-list-featured-image-column'); ?></p>
                        <input type="text" name="alfic_column_label" value="<?php echo esc_attr(get_option('alfic_column_label', 'Thumbnail')); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Max Width', 'admin-list-featured-image-column'); ?></th>
                    <td>
                        <p><?php esc_html_e('Enter the maximum width for the thumbnail image column in pixels.', 'admin-list-featured-image-column'); ?></p>
                        <input type="text" name="alfic_max_width" value="<?php echo esc_attr(get_option('alfic_max_width', '150')); ?>">
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Save settings
function alfic_save_settings()
{
    // Check if the form is submitted and the user has the necessary permissions
    if (isset($_POST['submit']) && current_user_can('manage_options')) {

        // Verify the nonce
        if (isset($_POST['alfic_settings_nonce']) && wp_verify_nonce($_POST['alfic_settings_nonce'], 'alfic_save_settings')) {

            // Process the form data and save the settings

            // Sanitize and save the selected post types
            if (isset($_POST['alfic_post_types']) && is_array($_POST['alfic_post_types'])) {
                $post_types = array_map('sanitize_text_field', $_POST['alfic_post_types']);
                update_option('alfic_post_types', $post_types);
            }

            // Sanitize and save the column label
            if (isset($_POST['alfic_column_label'])) {
                $column_label = sanitize_text_field($_POST['alfic_column_label']);
                update_option('alfic_column_label', $column_label);
            }

            // Sanitize and save the max width
            if (isset($_POST['alfic_max_width'])) {
                $max_width = absint($_POST['alfic_max_width']);
                update_option('alfic_max_width', $max_width);
            }

            // Redirect to the settings page with a success message
            wp_redirect(add_query_arg('updated', 'true', admin_url('options-general.php?page=admin-list-featured-image-column')));
            exit;
        }
    }
}
add_action('admin_post_alfic_save_settings', 'alfic_save_settings');

// Add featured image thumbnail column to post lists
function alfic_add_columns($columns)
{
    $post_types = get_option('alfic_post_types', array());
    $column_label = get_option('alfic_column_label', 'Thumbnail');
    $max_width = get_option('alfic_max_width', 150);

    // Check if the current post type is in the selected post types (including 'page')
    if (in_array(get_current_screen()->post_type, $post_types) || get_current_screen()->post_type === 'page') {
        // Add the thumbnail column
        $new_columns = array(
            'thumbnail' => $column_label, // Thumbnail column
        );

        // Insert the thumbnail column after the checkbox column and before the title column
        $position = array_search('cb', array_keys($columns)) + 1;
        $columns = array_slice($columns, 0, $position, true) + $new_columns + array_slice($columns, $position, null, true);

        // Add the custom column content
        add_action("manage_pages_custom_column", "alfic_content", 10, 2);
        add_action("manage_posts_custom_column", "alfic_content", 10, 2);

        // Make the thumbnail column sortable
        add_filter('manage_edit-page_sortable_columns', "alfic_sortable_columns");
        add_filter('manage_edit-post_sortable_columns', "alfic_sortable_columns");

        // Check if the current post type is a custom post type and make the thumbnail column sortable
        if (in_array(get_current_screen()->post_type, $post_types)) {
            $sortable_columns = get_option('alfic_sortable_columns', array());
            $sortable_columns['thumbnail'] = 'thumbnail';
            update_option('alfic_sortable_columns', $sortable_columns);
            add_filter('manage_edit-' . get_current_screen()->post_type . '_sortable_columns', "alfic_sortable_columns");
        }

        // Modify the query to sort by the presence of a thumbnail
        add_action('pre_get_posts', "alfic_modify_query");
    }

    // Make the featured image thumbnail column responsive
    echo '<style>
        .wp-list-table tr:not(.inline-edit-row):not(.no-items) .column-thumbnail {
            max-width: ' . esc_attr($max_width) . 'px;
            overflow: hidden;
            white-space: nowrap;
            width: ' . esc_attr($max_width) . 'px;
        }
        .wp-list-table tr:not(.inline-edit-row):not(.no-items) .column-thumbnail .admin-list-featured-image-column {
            text-align:center;
            white-space: initial;
        }
        .wp-list-table tr:not(.inline-edit-row):not(.no-items) .column-thumbnail .admin-list-featured-image-column img {
            display: block;
            height: auto;
            max-width: 100%;
            width: 100%;
        }
        @media screen and (max-width: 782px) {
            .wp-list-table tr:not(.inline-edit-row):not(.no-items) td.column-thumbnail::before {
              display: none;
            }
        }
    </style>';

    return $columns;
}

// Custom column content
function alfic_content($column, $post_id)
{
    $column_label = get_option('alfic_column_label', 'Thumbnail');
    $max_width = get_option('alfic_max_width', 150);

    if ($column === 'thumbnail') {
        $thumbnail_id = get_post_thumbnail_id($post_id);
        if ($thumbnail_id) {
            $image_attributes = wp_get_attachment_image_src($thumbnail_id, array($max_width, $max_width));
            if ($image_attributes) {
                $image = '<img src="' . esc_url($image_attributes[0]) . '"  alt="' . esc_attr__('Thumbnail') . '" width="' . esc_attr($image_attributes[1]) . '" height="' . esc_attr($image_attributes[2]) . '">';
                echo '<div class="admin-list-featured-image-column">' . $image . '</div>';
            }
        } else {
            $text = nl2br(str_replace(' ', '<br>', 'Image Not Selected'));
            echo '<div class="admin-list-featured-image-column">' . $text . '</div>';
        }
    }
}

// Make the thumbnail column sortable
function alfic_sortable_columns($sortable_columns)
{
    $sortable_columns['thumbnail'] = 'thumbnail';
    return $sortable_columns;
}

// Modify the query to sort by the presence of a thumbnail
function alfic_modify_query($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ($orderby === 'thumbnail') {
        $query->set('meta_key', '_thumbnail_id');
        $query->set('orderby', 'meta_value_num');
    }
}

add_filter('manage_posts_columns', 'alfic_add_columns', 5);
add_filter('manage_pages_columns', 'alfic_add_columns', 5);
