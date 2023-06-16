# Admin List Featured Image Column

WordPress plugin that enables post-thumbnails and adds featured image thumbnails to admin list columns for selected post types. 

Use Settings > Admin List Featured Image Column to select which post types show the column, define column label, and set the column thumbnail max width.

## Features

- Set which Post types show the thumbnail column in list view, including custom post types.
- Set label for the column, such as 'Featured Image', 'Thumbnails', or 'Preview',
- Set the column width to make the image larger or smaller.
- Column is always the first column after check boxes.


## Screenshots

Settings screen

![admin-list-featured-image-columns-settings-page-screenshot](https://github.com/danpoynor/admin-list-featured-image-column/assets/764270/4ee5578f-dc94-413f-9e2f-fe1facd85325)


Example column display

![admin-list-featured-image-columns-thumbnail-column-example-screenshot](https://github.com/danpoynor/admin-list-featured-image-column/assets/764270/730125f5-003b-49c1-8c0a-078df0f7ee13)


## Potential To-Do List

- Add uninstall feature to remove settings from db.
- Fix sorting bug so posts with missing images can be listed first.
- Allow adding/removing the featured image from the Quick Edit panel.
- On admin list pages, for any posts that don't have a featured image set yet, allow users to click on "Set featured image" in the thumbnail area to open the "Featured image" modal and select an image.
- Add error handling and feedback to notify users of any issues that may occur during plugin activation or configuration.
- Move configuration settings such as post types, column label, and max width into a separate file or array. This will make it easier to manage and update these settings in the future.
- Add setting for column order.
- Add support for other languages.
- Test with a bunch of other plugins to check for conflicts.
- Add unit tests.
- Submit to <https://wordpress.org/plugins/>
