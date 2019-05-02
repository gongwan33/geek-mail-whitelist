<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !defined( 'GMW_NAME' ) ) {
	define( 'GMW_NAME', 'Geek Mail Whitelist' );
}

if ( !defined( 'GMW_PLUGIN_FILE' ) ) {
	define( 'GMW_PLUGIN_FILE', __FILE__ );
}

if ( !defined( 'GMW_SLUG_NAME' ) ) {
	define( 'GMW_SLUG_NAME', 'geek-mail-whitelist' );
}

if ( !defined( 'GMW_MENU_ITEM' ) ) {
	define( 'GMW_MENU_ITEM', 'gmw_menu' );
}

if ( !defined( 'GMW_DB_NAME' ) ) {
	define( 'GMW_DB_NAME', 'gmw_whitelist' );
}

if ( !defined( 'GMW_PATH' ) ) {
	define( 'GMW_PATH', dirname( __FILE__ ));
}

if ( !defined( 'GMW_URL' ) ) {
	define( 'GMW_URL', plugins_url( '', __FILE__ ) );
}
?>
