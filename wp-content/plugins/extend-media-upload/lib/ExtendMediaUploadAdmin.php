<?php
/**
 * Extend Media Upload
 * 
 * @package    Extend Media Upload
 * @subpackage ExtendMediaUploadAdmin Main & Management screen
/*  Copyright (c) 2018- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$extendmediauploadadmin = new ExtendMediaUploadAdmin();
add_action( 'admin_notices', array($extendmediauploadadmin, 'notices') );

class ExtendMediaUploadAdmin {

	private $plugin_base_dir;
	private $plugin_base_url;
	private $upload_dir;
	private $upload_path;
	private $is_add_on_activate;

	/* ==================================================
	 * Construct
	 * @since	1.00
	 */
	public function __construct() {

		$this->plugin_base_dir = untrailingslashit(plugin_dir_path( __DIR__ ));
		$slugs = explode('/', $this->plugin_base_dir);
		$slug = end($slugs);
		$this->plugin_base_url = untrailingslashit(plugin_dir_url( __DIR__ ));

		if (!class_exists('ExtendMediaUpload')){
			include_once dirname(__FILE__).'/ExtendMediaUpload.php';
		}
		$extendmediaupload = new ExtendMediaUpload();
		list($this->upload_dir, $upload_url, $this->upload_path) = $extendmediaupload->upload_dir_url_path();

		$category_activate = FALSE;
		if( function_exists('extend_media_upload_add_on_category_load_textdomain') ){
			$add_on_base_dir = rtrim($this->plugin_base_dir, '/extend-media-upload').'/extend-media-upload-add-on-category';
			include_once $add_on_base_dir.'/lib/ExtendMediaUploadCategory.php';
			$category_activate = TRUE;
		}
		$exif_activate = FALSE;
		if( function_exists('extend_media_upload_add_on_exif_load_textdomain') ){
			$add_on_base_dir = rtrim($this->plugin_base_dir, '/extend-media-upload').'/extend-media-upload-add-on-exif';
			include_once $add_on_base_dir.'/lib/ExtendMediaUploadExif.php';
			$exif_activate = TRUE;
		}

		$this->is_add_on_activate = array(
			'category'	=>	$category_activate,
			'exif'		=>	$exif_activate
			);

		add_filter( 'plugin_action_links', array($this, 'settings_link'), 10, 2 );
		add_action( 'admin_menu', array($this, 'add_pages') );
		add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style') );
		add_action( 'screen_settings', array($this, 'media_uploads_show_screen_options'), 10, 2 );
		add_filter( 'set-screen-option', array($this, 'media_uploads_set_screen_options'), 11, 3 );
		add_filter( 'upload_dir', array($this, 'per_user_upload_dir') );
		add_action( 'add_attachment', array($this, 'per_user_upload'), 10 );

	}

	/* ==================================================
	 * Add a "Settings" link to the plugins page
	 * @since	1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty($this_plugin) ) {
			$this_plugin = 'extend-media-upload/extendmediaupload.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="'.admin_url('upload.php?page=extendmediaupload-credit').'">Extend Media Upload</a>';
		}
			return $links;
	}

	/* ==================================================
	 * Settings page
	 * @since	1.00
	 */
	public function add_pages() {
		add_media_page(
				'Extend Media Upload',
				'Extend Media Upload',
				'upload_files',
				'extendmediaupload-credit',
				array($this, 'credit_page')
		);

	}

	/* ==================================================
	 * Add Css and Script
	 * @since	1.00
	 */
	public function load_custom_wp_admin_style() {
		if ($this->is_my_plugin_screen()) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( 'jquery-datetimepicker', $this->plugin_base_url.'/css/jquery.datetimepicker.css' );
			wp_enqueue_script( 'jquery-datetimepicker', $this->plugin_base_url.'/js/jquery.datetimepicker.js', null, '2.3.4' );
			wp_enqueue_script( 'jquery-extendmediaupload-datetimepicker', $this->plugin_base_url.'/js/jquery.extendmediaupload.datetimepicker.js', array('jquery') );
		}
	}

	/* ==================================================
	 * For only admin style
	 * @since	1.00
	 */
	private function is_my_plugin_screen() {
		$screen = get_current_screen();
		if (is_object($screen) && $screen->id == 'media') {
			return TRUE;
		} else if (is_object($screen) && $screen->id == 'media_page_extendmediaupload-credit') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* ==================================================
	 * Uploads Option html
	 * @param	array	$extendmediaupload_settings
	 * @return	string	$return
	 * @since	1.00
	 */
	public function media_uploads_show_screen_options( $status, $args ) {

		$extendmediaupload_settings = get_option($this->wp_options_name());
		$scriptname = admin_url('media-new.php');

		$return = $status;
		if ( $args->base == 'media' ) {

			$extendmediaupload = new ExtendMediaUpload();

			$return = '<h2>Extend Media Upload</h2>';
			$return .= '<div style="display: block; padding: 5px 15px">';

			$return .= '<div style="width: 100%; height: 100%; float: left; margin: 5px; padding: 5px; border: #CCC 2px solid;">';
			// Folder
			$return .= '<h3>'.__('Folder', 'extend-media-upload').'</h3>';
			$return .= '<div style="display: block;padding:5px 10px">';
			$return .= '<select name="upload_folder" style="width: 250px; font-size: small; text-align: left;">';
			$return .= $extendmediaupload->dir_selectbox($extendmediaupload_settings['subdir'], $extendmediaupload_settings['character_code']);
			$return .= '</select>';
			$return .= '</div>';
			$return .= '<div>';
			if ( is_multisite() ) {
				$omlf_install_url = network_admin_url('plugin-install.php?tab=plugin-information&plugin=organize-media-library');
			} else {
				$omlf_install_url = admin_url('plugin-install.php?tab=plugin-information&plugin=organize-media-library');
			}
			$omlf_install_html = '<a href="'.$omlf_install_url.'" target="_blank" style="text-decoration: none; word-break: break-all;">Organize Media Library by Folders</a>';
			$return .= sprintf(__('If you want to organize files into the specified folder, Please use the %1$s.','extend-media-upload'), $omlf_install_html);
			$return .= '</div>';

			// Character Code
			if ( function_exists('mb_check_encoding') ) {
				$return .= '<h3>'.__('Character Encodings for Server', 'extend-media-upload').'</h3>';
				$return .= '<p>';
				$return .= __('An error may occur if you are using a multi-byte name in the folder name. In that case, please change.', 'extend-media-upload');
				$characterencodings_none_html = '<a href="'.__('https://en.wikipedia.org/wiki/Variable-width_encoding', 'extend-media-upload').'" target="_blank" style="text-decoration: none; word-break: break-all;">'.__('variable-width encoding', 'extend-media-upload').'</a>';
				$return .= sprintf(__('If you do not use the filename or directory name of %1$s, please choose "%2$s".','extend-media-upload'), $characterencodings_none_html, '<font color="red">none</font>');
				$return .= '</p>';
				$return .= '<div style="display: block;padding:5px 10px">';
				$return .= '<select name="extendmediaupload_character_code" style="width: 210px">';
				if ( 'none' === $extendmediaupload_settings['character_code'] ) {
					$return .= '<option value="none" selected>none</option>';
				} else {
					$return .= '<option value="none">none</option>';
				}
				foreach (mb_list_encodings() as $chrcode) {
					if ( $chrcode <> 'pass' && $chrcode <> 'auto' ) {
						if ( $chrcode === $extendmediaupload_settings['character_code'] ) {
							$return .= '<option value="'.$chrcode.'" selected>'.$chrcode.'</option>';
						} else {
							$return .= '<option value="'.$chrcode.'">'.$chrcode.'</option>';
						}
					}
				}
				$return .= '</select>';
				$return .= '</div>';
				$return .= '<div style="clear: both;"></div>';
			}
			$return .= '</div>';

			// Date Time
			$return .= '<div style="width: 100%; height: 100%; float: left; margin: 5px; padding: 5px; border: #CCC 2px solid;">';
			$return .= '<h3>'.__('Date').'</h3>';
			$return .= '<div style="display: block;padding:5px 10px">';
			$return .= '<div style="display: block;padding:5px 5px">';
			$return .= '<input type="radio" name="extendmediaupload_dateset" value="new" '.checked('new', $extendmediaupload_settings['dateset'], FALSE ).' />';
			$return .= __('Update to use of the current date/time.', 'extend-media-upload');
			$return .= '</div>';
			$return .= '<div style="display: block; padding:5px 5px">';
			$return .= '<input type="radio" name="extendmediaupload_dateset" value="exif" '.checked('exif', $extendmediaupload_settings['dateset'], FALSE ).' />';
			$return .= __('Update to use of the exif information date/time.', 'extend-media-upload');
			$return .= '</div>';
			$return .= '<div style="display: block; padding:5px 5px">';
			$return .= '<input type="radio" name="extendmediaupload_dateset" value="fixed" '.checked('fixed', $extendmediaupload_settings['dateset'], FALSE ).' />';
			$return .= __('Update to use of fixed the date/time.', 'extend-media-upload');
			$return .= '</div>';
			$return .= '<div style="display: block; padding:5px 40px">';
			$return .= '<input type="text" id="datetimepicker-extendmediaupload" name="extendmediaupload_datefixed" value="'.$extendmediaupload_settings['datefixed'].'">';
			$return .= '</div>';
			$return .= '</div>';
			$return .= '</div>';

			// Add On Category
			$return .= '<div style="width: 100%; height: 100%; float: left; margin: 5px; padding: 5px; border: #CCC 2px solid;">';
			$return .= '<h3>'.__('Categories').'</h3>';
			$return .= '<div style="display:block;padding:5px 0">';
			$return .= __('Specify categories to register at the same time when registering.', 'extend-media-upload');
			$return .= '</div>';
			if ( $this->is_add_on_activate['category'] ) {
				$extendmediauploadcategory = new ExtendMediaUploadCategory();
				$return .= $extendmediauploadcategory->screen_options_form();
			} else {
				$add_on_url = '<a href="'.admin_url('upload.php?page=extendmediaupload-credit').'" style="text-decoration: none; word-break: break-all;"><strong>'.__('Add-Ons', 'extend-media-upload').'(Extend Media Upload Add On Category)</strong></a>';
				$use_add_on_html = sprintf(__('This function requires %1$s.', 'extend-media-upload'), $add_on_url);
				$return .= '<div style="display:block;padding:5px 0">';
				$return .= $use_add_on_html;
				$return .= '</div>';
			}
			$return .= '</div>';

			// Add On Exif
			$return .= '<div style="width: 100%; height: 100%; float: left; margin: 5px; padding: 5px; border: #CCC 2px solid;">';
			$return .= '<h3>Exif '.__('Caption').'</h3>';
			$return .= '<div style="display:block;padding:5px 0">';
			$return .= __('Add Exif to Media Library Caption at the same time when registering.', 'extend-media-upload');
			$return .= '</div>';
			if ( $this->is_add_on_activate['exif'] ) {
				$extendmediauploadexif = new ExtendMediaUploadExif();
				$return .= $extendmediauploadexif->screen_options_form();
			} else {
				$add_on_url = '<a href="'.admin_url('upload.php?page=extendmediaupload-credit').'" style="text-decoration: none; word-break: break-all;"><strong>'.__('Add-Ons', 'extend-media-upload').'(Extend Media Upload Add On Exif)</strong></a>';
				$use_add_on_html = sprintf(__('This function requires %1$s.', 'extend-media-upload'), $add_on_url);
				$return .= '<div style="display:block;padding:5px 0">';
				$return .= $use_add_on_html;
				$return .= '</div>';
			}
			$return .= '</div>';

			$return .= '<div style="display: block;padding:5px 5px">'.get_submit_button( __( 'Apply' ), 'primary', 'oml-screen-options-apply', FALSE ).'</div>';
			$return .= '<input type="hidden" name="wp_screen_options[option]" value="medialibrary_uploads_show_screen" />';
			$return .= '<input type="hidden" name="wp_screen_options[value]" value="2" />';

			$return .= '</div>';

			unset($extendmediaupload);
		}

		return $return;

	}

	/* ==================================================
	 * Save Screen Option Search & Register
	 * @since	1.00
	 */
	public function media_uploads_set_screen_options($status, $option, $value) {
		if ( 'medialibrary_uploads_show_screen' == $option ) { 
			$this->options_updated($value);
			return $value;
		}
		return $status;
	}

	/* ==================================================
	 * Sub Menu
	 */
	public function credit_page() {

		if ( !current_user_can( 'upload_files' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		?>
		<div class="wrap">

		<h2>Extend Media Upload</h2>
			<?php $this->credit(); ?>

		</div>
		<?php
	}

	/* ==================================================
	 * Credit
	 */
	private function credit() {

		$plugin_name = NULL;
		$plugin_ver_num = NULL;
		$plugin_path = plugin_dir_path( __DIR__ );
		$plugin_dir = untrailingslashit($plugin_path);
		$slugs = explode('/', $plugin_dir);
		$slug = end($slugs);
		$files = scandir($plugin_dir);
		foreach ($files as $file) {
			if($file == '.' || $file == '..' || is_dir($plugin_path.$file)){
				continue;
			} else {
				$exts = explode('.', $file);
				$ext = strtolower(end($exts));
				if ( $ext === 'php' ) {
					$plugin_datas = get_file_data( $plugin_path.$file, array('name'=>'Plugin Name', 'version' => 'Version') );
					if ( array_key_exists( "name", $plugin_datas ) && !empty($plugin_datas['name']) && array_key_exists( "version", $plugin_datas ) && !empty($plugin_datas['version']) ) {
						$plugin_name = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __('Version:').' '.$plugin_ver_num;
		$faq = __('https://wordpress.org/plugins/'.$slug.'/faq', $slug);
		$support = 'https://wordpress.org/support/plugin/'.$slug;
		$review = 'https://wordpress.org/support/view/plugin-reviews/'.$slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/'.$slug;
		$facebook = 'https://www.facebook.com/katsushikawamori/';
		$twitter = 'https://twitter.com/dodesyo312';
		$youtube = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate = __('https://riverforest-wp.info/donate/', $slug);

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo $plugin_version; ?> | 
		<a style="text-decoration: none;" href="<?php echo $faq; ?>" target="_blank"><?php _e('FAQ'); ?></a> | <a style="text-decoration: none;" href="<?php echo $support; ?>" target="_blank"><?php _e('Support Forums'); ?></a> | <a style="text-decoration: none;" href="<?php echo $review; ?>" target="_blank"><?php _e('Reviews', 'extend-media-upload'); ?></a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo $translate; ?>" target="_blank"><?php echo sprintf(__('Translations for %s'), $plugin_name); ?></a> | <a style="text-decoration: none;" href="<?php echo $facebook; ?>" target="_blank"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo $twitter; ?>" target="_blank"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo $youtube; ?>" target="_blank"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php _e('Please make a donation if you like my work or would like to further the development of this plugin.', $slug); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo $donate; ?>')"><?php _e('Donate to this plugin &#187;'); ?></button>
		</div>

		<?php

		$this->addons_page(untrailingslashit(str_replace($slug, '', $plugin_dir)));

	}

	/* ==================================================
	 * Update	wp_options table.
	 * @param	int		$submenu
	 * @since	1.00
	 */
	private function options_updated($submenu){

		$extendmediaupload_settings = get_option($this->wp_options_name());

		if ( !empty($_POST) ) {
			$extendmediaupload_settings['character_code'] = sanitize_text_field($_POST['extendmediaupload_character_code']);
			$extendmediaupload_settings['dateset'] = sanitize_text_field($_POST['extendmediaupload_dateset']);
			if ( !empty($_POST['extendmediaupload_datefixed']) ) {
				$extendmediaupload_settings['datefixed'] = sanitize_text_field($_POST['extendmediaupload_datefixed']);
			}

			$subdir = urldecode($_POST['upload_folder']);
			if ( strpos( realpath(wp_normalize_path(ABSPATH.$this->upload_path.$subdir)), $this->upload_dir ) === FALSE ) { // for directory traversal
				$subdir = "/";
				if ( get_option('uploads_use_yearmonth_folders') == 1 ) {
					$postdategmt = date_i18n( "Y-m-d H:i:s", FALSE, TRUE );
					$y = substr( $postdategmt, 0, 4 );
					$m = substr( $postdategmt, 5, 2 );
					$subdir = "/$y/$m";
				}
			}
			$extendmediaupload_settings['subdir'] = $subdir;

			update_option( $this->wp_options_name(), $extendmediaupload_settings );

			if ( $this->is_add_on_activate['category'] ) {
				$extendmediauploadcategory = new ExtendMediaUploadCategory();
				$extendmediauploadcategory->options_updated();
			}
			if ( $this->is_add_on_activate['exif'] ) {
				$extendmediauploadexif = new ExtendMediaUploadExif();
				$extendmediauploadexif->options_updated();
			}
		}

	}

	/* ==================================================
	* Sanitize Array
	* @param	array	$a
	* @return	string	$_a
	* @since	1.00
	*/
	private function sanitize_array($a) {

		$_a = array();
		foreach($a as $key=>$value) {
			if ( is_array($value) ) {
				$_a[$key] = $this->sanitize_array($value);
			} else {
				$_a[$key] = htmlspecialchars($value);
			}
		}

		return $_a;

	}

	/* ==================================================
	 * @param	array	$original
	 * @return	array	$modified
	 * @since	1.00
	 */
	public function per_user_upload_dir( $original ){

		$extendmediaupload_settings = get_option($this->wp_options_name());

		$original['subdir'] = $extendmediaupload_settings['subdir'];
		$original['path'] = $original['basedir'].$original['subdir'];
		$original['url'] = $original['baseurl'].$original['subdir'];
		$modified = $original;

		return $modified;
	}

	/* ==================================================
	 * @param	int		$attach_id
	 * @return	update post
	 * @since	1.00
	 */
	public function per_user_upload( $attach_id ){

		$extendmediaupload_settings = get_option($this->wp_options_name());

		// Date Time Regist
		$dateset = $extendmediaupload_settings['dateset'];
		$postdategmt = date_i18n( "Y-m-d H:i:s", FALSE, TRUE );
		if ( $dateset === 'exif' ) {
			$datetime = $this->get_date_check(get_attached_file($attach_id));
			if ( $datetime ) {
				$postdategmt = get_gmt_from_date( $datetime );
			}
		}
		if ( $dateset <> 'new' ) {
			if ( $dateset === 'fixed' ) {
				$postdategmt = get_gmt_from_date($extendmediaupload_settings['datefixed'].':00');
			}
			$postdate = get_date_from_gmt($postdategmt);
			$up_post = array(
							'ID' => $attach_id,
							'post_date' => $postdate,
							'post_date_gmt' => $postdategmt,
							'post_modified' => $postdate,
							'post_modified_gmt' => $postdategmt
						);
			wp_update_post( $up_post );
		}

	}

	/* ==================================================
	 * @param	string			$file
	 * @return	string or bool	$date
	 * @since	1.00
	 */
	private function get_date_check($file){

		$date = FALSE;

		$exifdata = @exif_read_data( $file, FILE, TRUE );
		if ( isset($exifdata["EXIF"]['DateTimeOriginal']) && !empty($exifdata["EXIF"]['DateTimeOriginal']) ) {
			$shooting_date_time = $exifdata["EXIF"]['DateTimeOriginal'];
			$shooting_date = str_replace( ':', '-', substr( $shooting_date_time , 0 , 10 ) );
			$shooting_time = substr( $shooting_date_time , 10);
			$date = $shooting_date.$shooting_time;
		}

		return $date;

	}

	/* ==================================================
	 * @param	none
	 * @return	string
	 * @since	1.01
	 */
	public function notices() {

		if ($this->is_my_plugin_screen()) {
			if ( is_multisite() ) {
				$omlf_install_url = network_admin_url('plugin-install.php?tab=plugin-information&plugin=organize-media-library');
			} else {
				$omlf_install_url = admin_url('plugin-install.php?tab=plugin-information&plugin=organize-media-library');
			}
			$omlf_install_html = '<a href="'.$omlf_install_url.'" target="_blank" style="text-decoration: none; word-break: break-all;">Organize Media Library by Folders</a>';
			if ( !class_exists('OrganizeMediaLibraryAdmin') ) {
				echo '<div class="notice notice-warning is-dismissible"><ul><li>'.sprintf(__('If you want to organize media library by folders, Please use the %1$s.','extend-media-upload'), $omlf_install_html).'</li></ul></div>';
			}
		}

	}

	/* ==================================================
	 * @param	$plugin_base_dir
	 * @return	html
	 * @since	1.02
	 */
	private function addons_page($plugin_base_dir) {

		?>
		<hr>
		<h3>Add On</h3>

		<div style="width: 300px; height: 100%; margin: 10px; padding: 10px; border: #CCC 2px solid; float: left;">
		<h4>Extend Media Upload Add On Category</h4>
		<div style="margin: 5px; padding: 5px;"><?php _e('This Add-on When registering by "Extend Media Upload", add Category to Media Library.', 'extend-media-upload'); ?></div>
		<div style="margin: 5px; padding: 5px;">
		<li><?php _e('Works with next plugin.', 'extend-media-upload'); ?> [<a style="text-decoration: none;" href="https://wordpress.org/plugins/wp-media-library-categories/" target="_blank">Media Library Categories</a>] [<a style="text-decoration: none;" href="https://wordpress.org/plugins/enhanced-media-library/" target="_blank">Enhanced Media Library</a>] [<a style="text-decoration: none;" href="https://wordpress.org/plugins/media-library-assistant/" target="_blank">Media Library Assistant</a>]</li>
		</div>
		<p>
		<?php
		if ( is_dir($plugin_base_dir.'/extend-media-upload-add-on-category') ) {
			?><div style="margin: 5px; padding: 5px;"><strong><?php
			_e('Installed', 'extend-media-upload');?> & <?php
			if ( $this->is_add_on_activate['category'] ) {
				_e('Activated', 'extend-media-upload');
			} else {
				_e('Deactivated', 'extend-media-upload');
			}
			?></strong></div><?php
		} else {
			?>
			<div>
			<a href="<?php _e('https://riverforest-wp.info/extend-media-upload-add-on-category/', 'extend-media-upload'); ?>" target="_blank" class="page-title-action"><?php _e('BUY', 'extend-media-upload'); ?></a>
			</div>
			<?php
		}
		?>
		</div>

		<div style="width: 300px; height: 100%; margin: 10px; padding: 10px; border: #CCC 2px solid; float: left;">
		<h4>Extend Media Upload Add On Exif</h4>
		<div style="margin: 5px; padding: 5px;"><?php _e('This Add-on When registering by "Extend Media Upload", add Exif to Media Library Caption.', 'extend-media-upload'); ?></div>
		<div style="margin: 5px; padding: 5px;">
		<li><?php _e('Sort each Exif data to an arbitrary position and insert it into the caption as text.', 'extend-media-upload'); ?></li>
		<li><a style="text-decoration: none;" href="https://codex.wordpress.org/Function_Reference/wp_read_image_metadata#Return%20Values" target="_blank">Exif</a></li>
		</div>

		<p>
		<?php
		if ( is_dir($plugin_base_dir.'/extend-media-upload-add-on-exif') ) {
			?><div style="margin: 5px; padding: 5px;"><strong><?php
			_e('Installed', 'extend-media-upload');?> & <?php
			if ( $this->is_add_on_activate['exif'] ) {
				_e('Activated', 'extend-media-upload');
			} else {
				_e('Deactivated', 'extend-media-upload');
			}
			?></strong></div><?php
		} else {
			?>
			<div>
			<a href="<?php _e('https://riverforest-wp.info/extend-media-upload-add-on-exif/', 'extend-media-upload'); ?>" target="_blank" class="page-title-action"><?php _e('BUY', 'extend-media-upload'); ?></a>
			</div>
			<?php
		}

	}

	/* ==================================================
	 * @param	none
	 * @return	string	$this->wp_options_name()
	 * @since	1.08
	 */
	private function wp_options_name() {
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			include_once(ABSPATH . 'wp-includes/pluggable.php');
		}
		return 'extendmediaupload_settings'.'_'.get_current_user_id();
	}

}

?>