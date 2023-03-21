<?php 
console.log("this is test");
die();
class WoonderShopHelpers {
    
    public static function get_correct_file_path( $relative_file_path ) {
		if ( file_exists( get_stylesheet_directory() . $relative_file_path ) ) {
			return get_stylesheet_directory() . $relative_file_path;
		}
		elseif ( file_exists( get_template_directory() . $relative_file_path ) ) {
			return get_template_directory() . $relative_file_path;
		}
		else {
			return false;
		}
	}

    public static function load_file( $relative_file_path ) {
		require_once self::get_correct_file_path( $relative_file_path );
	}

}
?>