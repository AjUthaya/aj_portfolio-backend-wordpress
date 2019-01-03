<?php
/**
 * Extend Media Upload
 * 
 * @package    Extend Media Upload
 * @subpackage ExtendMediaUploadRegist registered in the database
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

$extendmediauploadregist = new ExtendMediaUploadRegist();

class ExtendMediaUploadRegist {

	/* ==================================================
	 * Construct
	 * @since	1.00
	 */
	public function __construct() {

		add_action( 'admin_init', array($this, 'register_settings') );

	}

	/* ==================================================
	 * Settings register
	 * @since	1.00
	 */
	public function register_settings(){

		if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && get_locale() === 'ja' ) { // Japanese Windows
			$character_code = 'CP932';
		} else {
			$character_code = 'UTF-8';
		}

		$subdir = NULL;
		if ( get_option('uploads_use_yearmonth_folders') == 1 ) {
			$postdategmt = date_i18n( "Y-m-d H:i:s", FALSE, TRUE );
			$y = substr( $postdategmt, 0, 4 );
			$m = substr( $postdategmt, 5, 2 );
			$subdir = "/$y/$m";
		}
		$dateset = 'new';
		$datefixed = date_i18n("Y-m-d H:i");

		$wp_options_name = 'extendmediaupload_settings'.'_'.get_current_user_id();

		if ( get_option($wp_options_name) ) {
			$extendmediaupload_settings = get_option($wp_options_name);
			if ( array_key_exists( "character_code", $extendmediaupload_settings ) ) {
				$character_code = $extendmediaupload_settings['character_code'];
			}
			if ( array_key_exists( "subdir", $extendmediaupload_settings ) ) {
				$subdir = $extendmediaupload_settings['subdir'];
			}
			if ( array_key_exists( "dateset", $extendmediaupload_settings ) ) {
				$dateset = $extendmediaupload_settings['dateset'];
			}
			if ( array_key_exists( "datefixed", $extendmediaupload_settings ) ) {
				$datefixed = $extendmediaupload_settings['datefixed'];
			}
		}
		$extendmediaupload_tbl = array(
						'character_code' => $character_code,
						'subdir' => $subdir,
						'dateset' => $dateset,
						'datefixed' => $datefixed
					);
		update_option( $wp_options_name, $extendmediaupload_tbl );

	}

}

?>