<?php
/**
 * Nomad Virtual Pages functions file.
 *
 * @since 1.0.0
 *
 * @package NomadVirtualPages
 */

namespace Nomad\VirtualPages;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'NOMAD_VIRTUAL_PAGES_VERSION' ) ) {

	/**
	 * Define the Nomad Virtual Pages Version constant.
	 *
	 * @since 1.0.0
	 */
	define( 'NOMAD_VIRTUAL_PAGES_VERSION', '1.0.0' );

}

if ( ! function_exists( __NAMESPACE__ . '\\nomad_virtual_pages_trigger_404' ) ) {

	/**
	 * Triggers a 404 Error and display the WordPress theme 404 template.
	 *
	 * @since 1.0.0
	 */
	function nomad_virtual_pages_trigger_404() {

		global $wp_query;

		$wp_query->set_404();

		status_header( 404 );

		get_template_part( '404' );
		exit;

	}

}

if ( ! function_exists( __NAMESPACE__ . '\\nomad_register_virtual_page' ) ) {

	/**
	 * Register a single Virtual Page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $virtual_page_name  The Virtual Page Name to be registered.
	 * @param string $virtual_page_class The Virtual Page Class to be registered.
	 */
	function nomad_register_virtual_page( string $virtual_page_name, string $virtual_page_class ) {

		Nomad_Virtual_Pages::instance()->register_virtual_page( $virtual_page_name, $virtual_page_class );

	}

}

if ( ! function_exists( __NAMESPACE__ . '\\nomad_register_virtual_pages' ) ) {

	/**
	 * Register multiple Virtual Pages.
	 *
	 * @since 1.0.0
	 *
	 * @param array $virtual_pages An array of Virtual Page name and class names of Virtual Pages to register.
	 */
	function nomad_register_virtual_pages( array $virtual_pages ) {

		Nomad_Virtual_Pages::instance()->register_virtual_pages( $virtual_pages );

	}

}
