=== Physical Custom Upload Folder ===
Contributors: mguenter
Tags: media library folders, folders, media categories, media folders, media category, media folder, rml, media library, real media library, files, media, organize, upload, ftp, uploads, wp-content
Stable tag: trunk
Requires at least: 4.0
Tested up to: 5.0
License: GPLv2

Physically organize your media library uploads (Real Media Library extension).

== Description ==

Do you know the **wp-content/uploads** folder? There, the files are stored in year/month based folders. This can be a very complicated and mass process, especially when you are working with a FTP client like FileZilla.

With this plugin you can determine where to store your uploads. This can also have some **SEO benefits**.

This plugin is an extension for the [WP Real Media Library](https://codecanyon.net/item/wordpress-real-media-library-media-categories-folders/13155134) plugin that allows you to create folders in media library.
This plugin needs version >= 2.8 of the WP Real Media Library plugin.

**Create thumbnails folders**: As you can see in the header screenshot above, there is also a folder with the thumbnails in it (of each image). This feature is not coming with this plugin, you need to install [WP Real Thumbnail Generator](https://codecanyon.net/item/wordpress-real-thumbnail-generator-bulk-regenerate-upload-folder/18937507).

**Moving already uploaded files**: This light plugin does not allow to move the files physically when you move a file in the Real Media Library because WordPress uses the URL's in different places. You need the Pro version to allow this feature.

---

**[PRO Version](https://matthias-web.com/go/codecanyon/23104206): Since January 2019 there is a Pro Version available with the following features:**

- Allow moving already uploaded files with a **queue**-based system
- **Manual queueing** allows you to add single files to the queue when you do not want to activate automatic queueing
- Activate automatic queueing: **Every file movement** gets tracked and added to the queue **automatically** (move, rearrange, bulk move, rename folder)
- **SEO URL rewrites** protects your from lost references: If another website is referencing to a given file the URL does not become invalid (301, 302 SEO redirect)
- **Exclude** specific folders from physical path
- Initially add your **complete** media library (existing files) to the queue
- Setup an additional **uploads prefix**: The prefix gets appended to your defined uploads folder, for example wp-content/uploads/storage
- The plugin provides a link so you can setup a **cronjob** which does process the queue all the day
- Compatible with Real Media Library add-ons like WP/LR Sync Folders
- Works with all your available media library files like **PDFs, Office files and images**
- One-click import virtual folder structure into physical folder structure
- **Independent:** Your files stay in the physical folders also when deleting this plugin
- **Migration-safe:** You can migrate your pages as usual. There is nothing else to consider
- Advanced options: If you ever get the error message **Could not insert post into the database.** after uploading a file you can extend the default WordPress database table. So more then **255 characters** are allowed in the URL path / filename
- Supports multisite
- 6 months support Included
- Forever free updates

[→ Interested in the Pro version?](https://matthias-web.com/go/codecanyon/23104206)

== Installation ==

1. Goto your wordpress backend
2. Navigate to Plugins > Add new
3. Search for "Physical Custom Upload Folder"
4. "Install"

OR 

1. Copy the `upload_dir-real_media_library` folder into your `wp-content/plugins` folder
2. Activate the Real Media Library plugin via the plugins admin page
3. Upload a file to a folder and see what happens in your wp-content/uploads folder

== Frequently Asked Questions ==
= How does this plugin work? =
The steps are simple: Activate both plugins (Real Media Library and this one), navigate to Media Library > Add new. Simply select the destination folder and upload a new file. The new uploaded file will be physically placed to your RML absolute path. This does also work in Media Library grid mode: Select a folder in the folders tree upload a new file.

== Changelog ==

= 1.0.5 =
* Added link to PRO version in plugin row
* Fixed bug with PHP 7.3 (deprecation notice in error log)

= 1.0.4 =
* Fixed bug with pathes generation
* Removed unnecessery code blocks

= 1.0.3 =
* Fixed incompatibility with RML version 2.8.2

= 1.0.1 & 1.0.2 =
* Failure version commits

= 1.0.0 =
* Initial Release.