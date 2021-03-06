<?php
/*
  Plugin Name: Geek Mail Whitelist
  Plugin URI: https://geekblog.mybluemix.net/archives/731
  Description: Allow users with certain emails to register
  Author: gongwan33
  Version: 1.0.0

/*

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

require_once(dirname(__FILE__).'/variables.php');
require_once(GMW_PATH . '/lib/GMW.php');
require_once(GMW_PATH.'/backend/actions.php');

register_activation_hook( __FILE__, array( 'GMW', 'install' ) );
register_deactivation_hook( __FILE__, array( 'GMW', 'uninstall' ) );
GMW::init();

if(!GMW::check_database_exists(GMW_DB_NAME)) {
    //install corrupted
    //reinstall
    GMW::install();
}

GMWActions::init();

