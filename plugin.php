<?php
/**
 * Plugin Name: is_hm_admin
 * Description: Adds a custom capability and some helper functions to determine if the current user is a privileged Human Made user.
 * Version:     1.4.0
 * Author:      Human Made
 * Author URI:  http://hmn.md
 * License:     GPLv2
 *
 * @package HM\Is_Admin
 */

use HM\Is_Admin;

require_once __DIR__ . '/inc/class-is-hm-admin.php';

/**
 * Copyright (c) 2016 Human Made
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Grab the Is_HM_Admin object and return it.
 * Wrapper for Is_HM_Admin::get_instance()
 *
 * @since  1.0.0
 * @return Is_HM_Admin  Singleton instance of plugin class.
 */
function hm_is_admin() {
	return HM\Is_Admin\Is_HM_Admin::get_instance();
}

/**
 * Checks if the current user is hm_admin and has special capabilities.
 *
 * @param  int|object $user         A specific user to check. Can be the WP_User object or an ID.
 * @param  boolean    $bypass_proxy Whether to bypass the HM proxy check. Defaults to false.
 * @return boolean
 */
function is_hm_admin( $user = false, $bypass_proxy = false ) {
	return hm_is_admin()->is_hm_admin( $user, $bypass_proxy );
}

// Kick it off.
add_action( 'admin_init', [ hm_is_admin(), 'add_cap_if_not_exists' ] );
