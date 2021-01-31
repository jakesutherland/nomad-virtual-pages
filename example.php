<?php
/**
 * Example implmentation of registering a new Nomad Virtual Page.
 *
 * @since 1.0.0
 *
 * @package NomadVirtualPages
 */

use Nomad\VirtualPages\Nomad_Virtual_Page;

use function Nomad\VirtualPages\nomad_register_virtual_pages;

/**
 * Nomad Virtual Pages must be registered on `plugins_loaded` hook.
 */
add_action( 'plugins_loaded', function() {

	nomad_register_virtual_pages( array(
		'nomad_user_login' => 'Nomad_User_Login_Virtual_Page',
	) );

} );

/**
 * Example Nomad Virtual Page.
 *
 * Must extend the `Nomad_Virtual_Page` class.
 *
 * @since 1.0.0
 * @final
 */
final class Nomad_User_Login_Virtual_Page extends Nomad_Virtual_Page {

	/**
	 * Get Virtual Page name.
	 *
	 * Used as a unique identifier for the Virtual Pages being registered
	 * in this class.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_name() {

		return 'nomad_user_login';

	}

	/**
	 * Get Virtual Page Rewrite Rules.
	 *
	 * Provide a multidimensional array where each entry contains the
	 * rewrite rule 'regex', 'query', and optionally the 'priority'.
	 * For more information see the WordPress `add_rewrite_rule` function.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_rewrite_rule/
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_rewrite_rules() {

		return array(
			array(
				'regex' => '^login/?$',
				'query' => 'index.php?nomad_user_login=login',
			),
			array(
				'regex' => '^logout/?$',
				'query' => 'index.php?nomad_user_login=logout',
			),
			array(
				'regex' => '^login/forgot-password/?$',
				'query' => 'index.php?nomad_user_login=forgot_password',
			),
		);

	}

	/**
	 * Get Virtual Page Query Vars.
	 *
	 * Provide an array of Nomad Query Var strings to register.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_query_vars() {

		return array(
			'nomad_user_login',
		);

	}

	/**
	 * Template Include
	 *
	 * Handle the template that should be used to render the virtual page.
	 *
	 * @since 1.0.0
	 * @abstract
	 *
	 * @param string $template The template to be included.
	 * @return string
	 */
	public function template_include( string $template ) {

		$nomad_user_login = get_query_var( 'nomad_user_login' );

		if ( $nomad_user_login ) {

			$new_template = '';

			switch ( $nomad_user_login ) {
				case 'login':
					$new_template = locate_template( 'page-user-login.php' );
				break;
				case 'logout':
					$new_template = locate_template( 'page-user-login.php' );
				break;
				case 'forgot_password':
					$new_template = locate_template( 'page-user-login.php' );
				break;
			}

			if ( $new_template ) {
				return $new_template;
			} else {
				// Example location of a plugin's default templates.
				$template = dirname( __FILE__ ) . '/templates/page-user-login.php';
			}

		}

		return $template;

	}

}
