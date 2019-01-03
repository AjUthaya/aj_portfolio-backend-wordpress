<?php
/**
 * Extend Media Upload
 * 
 * @package    Extend Media Upload
 * @subpackage ExtendMediaUpload Main Functions
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

class ExtendMediaUpload {

	private $upload_dir;
	private $upload_path;

	/* ==================================================
	 * Construct
	 * @since	1.00
	 */
	public function __construct() {

		list($this->upload_dir, $upload_url, $this->upload_path) = $this->upload_dir_url_path();

	}

	/* ==================================================
	 * @param	string	$dir
	 * @return	array	$dirlist
	 * @since	1.00
	 */
	private function scan_dir($dir) {

		$excludedir = 'media-from-ftp-tmp';	// tmp dir for Media from FTP
		global $blog_id;
		if ( is_multisite() && is_main_site($blog_id) ) {
			$excludedir .= '|\/sites\/';
		}

		$files = scandir($dir);
		$list = array();
		foreach ($files as $file) {
			if($file == '.' || $file == '..'){
				continue;
			}
			$fullpath = rtrim($dir, '/') . '/' . $file;
			if (is_dir($fullpath)) {
				if (!preg_match("/".$excludedir."/", $fullpath)) {
					$list[] = $this->mb_utf8($fullpath, 'UTF-8');
				}
				$list = array_merge($list, $this->scan_dir($fullpath));
			}
		}

		arsort($list);
		return $list;

	}

	/* ==================================================
	 * @param	string	$searchdir
	 * @param	string	$character_code
	 * @return	string	$dirlist
	 * @since	1.00
	 */
	public function dir_selectbox($searchdir, $character_code) {

		$dirs = $this->scan_dir($this->upload_dir);
		$linkselectbox = NULL;
		$wordpress_path = wp_normalize_path(ABSPATH);
		foreach ($dirs as $linkdir) {
			$linkdirenc = $this->mb_utf8(str_replace($this->upload_dir, '', $linkdir), $character_code);
			if( $searchdir === $linkdirenc ){
				$linkdirs = '<option value="'.urlencode($linkdirenc).'" selected>'.str_replace($this->upload_path, '', $linkdirenc).'</option>';
			}else{
				$linkdirs = '<option value="'.urlencode($linkdirenc).'">'.str_replace($this->upload_path, '', $linkdirenc).'</option>';
			}
			$linkselectbox = $linkselectbox.$linkdirs;
		}
		if( $searchdir ===  '/' ){
			$linkdirs = '<option value="'.urlencode('/').'" selected>/</option>';
		}else{
			$linkdirs = '<option value="'.urlencode('/').'">/</option>';
		}
		$linkselectbox = $linkselectbox.$linkdirs;

		return $linkselectbox;

	}

	/* ==================================================
	 * @param	string	$base
	 * @param	string	$relationalpath
	 * @return	string	realurl
	 * @since	1.00
	 */
	private function realurl( $base, $relationalpath ){
	     $parse = array(
	          "scheme" => null,
	          "user" => null,
	          "pass" => null,
	          "host" => null,
	          "port" => null,
	          "query" => null,
	          "fragment" => null
	     );
	     $parse = parse_url( $base );

	     if( strpos($parse["path"], "/", (strlen($parse["path"])-1)) !== false ){
	          $parse["path"] .= ".";
	     }

	     if( preg_match("#^https?://#", $relationalpath) ){
	          return $relationalpath;
	     }else if( preg_match("#^/.*$#", $relationalpath) ){
	          return $parse["scheme"] . "://" . $parse["host"] . $relationalpath;
	     }else{
	          $basePath = explode("/", dirname($parse["path"]));
	          $relPath = explode("/", $relationalpath);
	          foreach( $relPath as $relDirName ){
	               if( $relDirName == "." ){
	                    array_shift( $basePath );
	                    array_unshift( $basePath, "" );
	               }else if( $relDirName == ".." ){
	                    array_pop( $basePath );
	                    if( count($basePath) == 0 ){
	                         $basePath = array("");
	                    }
	               }else{
	                    array_push($basePath, $relDirName);
	               }
	          }
	          $path = implode("/", $basePath);
	          return $parse["scheme"] . "://" . $parse["host"] . $path;
	     }

	}

	/* ==================================================
	 * @param	none
	 * @return	array	$upload_dir, $upload_url, $upload_path
	 * @since	1.00
	 */
	public function upload_dir_url_path(){

		$wp_uploads = wp_upload_dir();

		$relation_path_true = strpos($wp_uploads['baseurl'], '../');
		if ( $relation_path_true > 0 ) {
			$relationalpath = substr($wp_uploads['baseurl'], $relation_path_true);
			$basepath = substr($wp_uploads['baseurl'], 0, $relation_path_true);
			$upload_url = $this->realurl($basepath, $relationalpath);
			$upload_dir = wp_normalize_path(realpath($wp_uploads['basedir']));
		} else {
			$upload_url = $wp_uploads['baseurl'];
			$upload_dir = wp_normalize_path($wp_uploads['basedir']);
		}

		if(is_ssl()){
			$upload_url = str_replace('http:', 'https:', $upload_url);
		}

		if ( $relation_path_true > 0 ) {
			$upload_path = $relationalpath;
		} else {
			$upload_path = str_replace(site_url('/'), '', $upload_url);
		}

		$upload_dir = untrailingslashit($upload_dir);
		$upload_url = untrailingslashit($upload_url);
		$upload_path = untrailingslashit($upload_path);

		return array($upload_dir, $upload_url, $upload_path);

	}

	/* ==================================================
	 * @param	string	$str
	 * @param	string	$character_code
	 * @return	string	$str
	 * @since	1.00
	 */
	private function mb_utf8($str, $character_code) {

		if ( function_exists('mb_convert_encoding') && $character_code <> 'none' ) {
			$str = mb_convert_encoding($str, "UTF-8", "auto");
		}

		return $str;

	}

}

?>