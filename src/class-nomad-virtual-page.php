<?php
/**
 * Nomad Virtual Page class file.
 *
 * @since 1.0.0
 *
 * @package NomadVirtualPages
 */

namespace Nomad\VirtualPages;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( __NAMESPACE__ . '\\Nomad_Virtual_Page' ) ) {

	/**
	 * Nomad Virtual Page class.
	 *
	 * An abstract class to register new Virtual Pages in WordPress.
	 *
	 * @since 1.0.0
	 * @abstract
	 */
	abstract class Nomad_Virtual_Page {

		/**
		 * Get Virtual Page name.
		 *
		 * Used as a unique identifier for the Virtual Pages being registered
		 * in this class.
		 *
		 * @since 1.0.0
		 * @abstract
		 *
		 * @return string
		 */
		abstract function get_name();

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

			return array();

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

			return array();

		}

		/**
		 * Add Virtual Page Query Vars.
		 *
		 * Adds query vars to the list of Nomad Query Vars to be registered.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $nomad_query_vars Nomad Query Vars to register.
		 * @return array
		 */
		public function add_query_vars( array $nomad_query_vars ) {

			$new_query_vars = $this->get_query_vars();

			if ( ! empty( $new_query_vars ) ) {
				$nomad_query_vars = array_merge( $nomad_query_vars, $new_query_vars );
			}

			return $nomad_query_vars;

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
		abstract function template_include( string $template );

		/**
		 * Constructor.
		 *
		 * Set up all necessary WordPress hooks and filters.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'nomad/virtual_pages/query_vars', array( $this, 'add_query_vars' ) );
			add_filter( 'nomad/virtual_pages/template', array( $this, 'template_include' ) );

			// Only hook to `nomad/virtual_pages/parse_request` if an actual `parse_request()` method exists.
			if ( method_exists( $this, 'parse_request' ) ) {
				add_action( 'nomad/virtual_pages/parse_request', array( $this, 'parse_request' ) );
			}

		}

	}

}
