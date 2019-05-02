<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

class GMW {

    public static $cssPath = null;
    public static $class = null;

    public static function init() {
        add_action('admin_menu', array(get_class(), 'registerAdminPages'));
        self::deployWhitelist();
    }

    public static function install() {
        global $wpdb;
        $table_name = $wpdb->prefix . GMW_DB_NAME; 

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            expression text NOT NULL,
            userid mediumint(9) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        $GMW_enabled = get_option('gmw-enabled');
        if(empty($GMW_enabled)) {
            add_option('gmw-enabled', 'no',  '', 'yes');
        }
    }

    public static function uninstall() {
        global $wpdb;
        $table_name = $wpdb->prefix . GMW_DB_NAME; 
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query( $sql );

        delete_option('gmw-enabled');
    }

    public static function isUserValid() {
        if(current_user_can('administrator')) {
            return true;
        } else {
            return false;
        }
    }

    public static function registerAdminPages() {
        if(self::isUserValid()) {
            add_menu_page('Settings', 'Geek Mail Whitelist', 'manage_options', GMW_MENU_ITEM, array(get_class(), 'displayAdminOptions'));
        }
    }

    public static function displayAdminOptions() {
        require_once GMW_PATH . '/backend/settings.php';
    }

    public static function deployWhitelist() {
        $enabled = get_option('gmw-enabled');
        if($enabled == 'yes') {
            add_filter( 'registration_errors', array(get_class(), 'GMW_check_fields'), 11, 3 );
        }
    } 

    public static function GMW_check_fields( $errors, $sanitized_user_login, $user_email ) { 
        global $wpdb;

        $table_name = $wpdb->prefix . GMW_DB_NAME; 
        $rules = $wpdb->get_results("SELECT expression FROM $table_name", ARRAY_A);
        $nomatch = true;
        
        if(!empty($rules)) {
            foreach($rules as $rule) {
                $exp = trim($rule['expression']);

                if(substr($exp, 0, 1) == '/' && substr($exp, -1, 1) == '/') {
                    $match_flag = preg_match($exp, $user_email, $matches);

                    if($match_flag) {
                        $nomatch = false;
                    }
                } else {
                    if($user_email == $exp) {
                        $nomatch = false;
                    }
                }
            }
        }

        if($nomatch) {
            $errors->add( 'demo_error', '<strong>ERROR</strong>: Sorry! Your Email is not valid or filtered.');
        }

        return $errors;
    }
}
?>
