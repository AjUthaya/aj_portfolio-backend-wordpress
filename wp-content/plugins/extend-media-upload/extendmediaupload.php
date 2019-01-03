<?php
/*
Plugin Name: Extend Media Upload
Plugin URI: https://wordpress.org/plugins/extend-media-upload/
Version: 1.09
Description: Add folder specification and date time specification to the media uploader.
Author: Katsushi Kawamori
Author URI: https://riverforest-wp.info/
Text Domain: extend-media-upload
Domain Path: /languages
*/

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

	add_action( 'plugins_loaded', 'extend_media_upload_load_textdomain' );
	function extend_media_upload_load_textdomain() {
		load_plugin_textdomain('extend-media-upload');
	}

	if(!class_exists('ExtendMediaUploadRegist')) require_once( dirname(__FILE__).'/lib/ExtendMediaUploadRegist.php' );
	if(!class_exists('ExtendMediaUploadAdmin')) require_once( dirname(__FILE__).'/lib/ExtendMediaUploadAdmin.php' );

?>
