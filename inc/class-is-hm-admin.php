<?php

namespace HM\Is_Admin;

/**
 * Main initiation class
 *
 * @since 1.0.0
 * @var   string $version  Plugin version
 * @var   string $basename Plugin basename
 * @var   string $url      Plugin URL
 * @var   string $path     Plugin Path
 */
class Is_HM_Admin {

	/**
	 * Current version
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * Plugin basename
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $basename = '';

	/**
	 * Privileged username
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	public $privileged_users = [];

	/**
	 * Singleton instance of plugin
	 *
	 * @var    Is_HM_Admin
	 * @since  1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  1.0.0
	 * @return Is_HM_Admin A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  1.0.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
	}

	/**
	 * Simply return our capability name, while allowing it to be filtered.
	 *
	 * @return string capability name
	 */
	public function get_cap_name() {
		return apply_filters( 'is_hm_admin_cap_name', 'is_hm_admin' );
	}

	/**
	 * Check if the current user is the privileged user.
	 *
	 * @since  1.0.0
	 * @return bool True/false depending if the current user is the one defined in the __construct as the privileged user.
	 */
	private function is_privileged_user() {

		// Allow for overriding of the entire check. This filter also runs at the end
		// of the function, but at that point contains our current_user object, so
		// more checking can be done.
		if ( apply_filters( 'is_hm_admin', false ) ) {
			return true;
		}

		$current_user = null;

		// If is_hm_admin() gets called to early, wp_get_current_user may not exist yet.
		// Rather than fatal erroing, we'll just skip the check and keep the $current_user
		// variable set to null, so it can still get passed into the comparision checks.
		if ( function_exists( 'wp_get_current_user' ) ) {
			$current_user = wp_get_current_user();
		}

		// If we don't have a current user, then they can't be a hm_admin.
		if ( is_wp_error( $current_user ) ) {
			return false;
		}

		// If we have a user_login for the current user object, compare that to our acceptable user array.
		if ( is_object( $current_user ) && isset( $current_user->user_login ) ) {

			// Allow our allowed domains to be filtered.
			$domains = apply_filters( 'hm_is_admin_allowed_domains', 'humanmade\.co\.uk|hmn\.md' );
			$allowed = (bool) preg_match( '/(' . $domains . ')$/', $current_user->user_email );

			return $allowed;
		}

		// Also allow for filtering globally.
		return apply_filters( 'is_hm_admin', false, $current_user );
	}

	/**
	 * Add the 'is_hm_admin' capability if it doesn't exist. Remove if the user should not have it.
	 *
	 * @since 1.0.0
	 */
	public function add_cap_if_not_exists() {

		// Check to see if the user is privileged.
		$is_priviledged = $this->is_privileged_user();

		// Check to see if the user currently has the is_hm_admin cap.
		$has_cap = current_user_can( $this->get_cap_name() );

		// Check to see if the current user is defined as the privileged user and
		// if they don't already have the 'is_hm_admin' capability.
		if ( $is_priviledged && ! $has_cap ) {
			// Add the cap to the user.
			return $this->modify_cap( 'add' );
		}

		// If the user is not privileged, but has the capability, let's remove it from them.
		if ( ! $is_priviledged && $has_cap ) {
			// Remove cap from user.
			return $this->modify_cap( 'remove' );
		}
	}

	/**
	 * Helper method to add or remove 'is_hm_admin' capabity for current user.
	 *
	 * @param  string $action 'add' or 'remove'.
	 */
	public function modify_cap( $action ) {

		// Get the WP_User object.
		$user = new WP_User( get_current_user_id() );

		// If we didn't get a user, bail out.
		if ( empty( $user ) || ! is_object( $user ) ) {
			return;
		}

		// Protect against adding the cap to users switched to via User Switching.
		if ( function_exists( 'current_user_switched' ) ) {
			if ( current_user_switched() && current_user_can( $this->get_cap_name ) ) {
				$user->remove_cap( $this->get_cap_name() );
				return;
			}
		}

		// Depening on the action that is being passed in, add or remove the capability for the user.
		switch ( $action ) {
			case 'add':
				$user->add_cap( $this->get_cap_name() );
				break;
			case 'remove':
				$user->remove_cap( $this->get_cap_name() );
				break;
		}
	}

	/**
	 * Master checker if the current user is privileged.
	 *
	 * @param  int|object $user         A specific user to check. Can be the WP_User object or an ID.
	 * @param  boolean    $bypass_proxy Allow the proxy check to be manually bypassed.
	 * @return boolean
	 */
	public function is_hm_admin( $user = false, $bypass_proxy = false ) {
		// If a user ID or object was passed, do a hard check to see if they have the capability. This check will run regardless of the proxy.
		if ( $user ) {
			return user_can( $user, $this->get_cap_name() );
		}

		// Check if the user is proxied in. This check supercedes all other checks.
		if ( defined( 'HM_IS_PROXIED' ) && ! $bypass_proxy ) {
			return HM\Proxy_Access\is_proxied();
		}

		return $this->is_privileged_user();
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  1.0.0
	 * @param  string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'privileged_user':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}
}
