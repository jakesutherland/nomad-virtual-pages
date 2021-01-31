<?php
/**
 * Nomad Virtual Pages class file.
 *
 * @since 1.0.0
 *
 * @package NomadVirtualPages
 */

namespace Nomad\VirtualPages;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( __NAMESPACE__ . '\\Nomad_Virtual_Pages' ) ) {

	/**
	 * Nomad Virtual Pages class.
	 *
	 * Allows the registration of Virtual Pages in WordPress.
	 *
	 * @since 1.0.0
	 * @final
	 */
	final class Nomad_Virtual_Pages {

		/**
		 * Instance.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var Nomad_Virtual_Pages The single instance of this class.
		 */
		private static $instance = null;

		/**
		 * Virtual Pages.
		 *
		 * Contains a key value pair of Nomad Virtual Page names and an
		 * instance of their extended `Nomad_Virtual_Page` class.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @var array All registered Nomad Virtual Pages.
		 */
		public $virtual_pages = array();

		/**
		 * Register multiple Nomad Virtual Pages.
		 *
		 * Loops through an array of key value pairs of Virtual Page Names and
		 * their extended `Nomad_Virtual_Page` class names.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $virtual_pages An array of Virtual Page name and class names of Virtual Pages to register.
		 */
		public function register_virtual_pages( array $virtual_pages ) {

			if ( ! is_iterable( $virtual_pages ) || empty( $virtual_pages ) ) {
				return;
			}

			foreach ( $virtual_pages as $virtual_page_name => $virtual_page_class ) {
				$this->register_virtual_page( $virtual_page_name, $virtual_page_class );
			}

		}

		/**
		 * Register a single Nomad Virtual Page.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param string $virtual_page_name  The Virtual Page Name to be registered.
		 * @param string $virtual_page_class The Virtual Page Class to be registered.
		 */
		public function register_virtual_page( string $virtual_page_name, string $virtual_page_class ) {

			if ( empty( $virtual_page_name ) || empty( $virtual_page_class ) ) {
				return;
			}

			if ( ! class_exists( $virtual_page_class ) ) {
				return;
			}

			if ( ! is_subclass_of( $virtual_page_class, __NAMESPACE__ . '\\Nomad_Virtual_Page' ) ) {
				return;
			}

			$this->virtual_pages[ $virtual_page_name ] = new $virtual_page_class();

		}

		/**
		 * Add Rewrite Rules from registered Nomad Virtual Pages.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function add_rewrite_rules() {

			if ( ! is_iterable( $this->virtual_pages ) || empty( $this->virtual_pages ) ) {
				return;
			}

			foreach ( $this->virtual_pages as $virtual_page ) {

				$rewrite_rules = $virtual_page->get_rewrite_rules();

				if ( ! is_iterable( $rewrite_rules ) || empty( $rewrite_rules ) ) {
					continue;
				}

				foreach ( $rewrite_rules as $rewrite_rule ) {

					// If a priority was not provided, default to 'top'. Rarely do you actually want 'bottom' even though that is the default.
					$rewrite_rule['priority'] = ( isset( $rewrite_rule['priority'] ) ) ? $rewrite_rule['priority'] : 'top';

					add_rewrite_rule( $rewrite_rule['regex'], $rewrite_rule['query'], $rewrite_rule['priority'] );

				}

			}

		}

		/**
		 * WordPress `query_vars` filter hook callback function.
		 *
		 * Implement our own query vars filter to combine everything into a
		 * single array to bunch it all up at once.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $query_vars Query vars registered with WordPress.
		 * @return array
		 */
		public function filter_query_vars( array $query_vars ) {

			/**
			 * Filter for defining Nomad Query Vars.
			 *
			 * Provide an array of query var strings to register with WordPress.
			 *
			 * @since 1.0.0
			 *
			 * @param array $query_vars An array of registered Nomad Query Vars.
			 */
			$nomad_query_vars = apply_filters( 'nomad/virtual_pages/query_vars', array() );

			return array_merge( $nomad_query_vars, $query_vars );

		}

		/**
		 * WordPress `template_include` filter hook callback function.
		 *
		 * Allows for us to implement our own hook to determine which template
		 * to use when a Nomad Virtual Page is being loaded.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param string $template The template to be used.
		 * @return string
		 */
		public function filter_template_include( string $template ) {

			/**
			 * Filter for determining which template to use when a Nomad
			 * Virtual Page is being loaded.
			 *
			 * @since 1.0.0
			 *
			 * @param string $template The template to be used.
			 */
			$template = apply_filters( 'nomad/virtual_pages/template', $template );

			return $template;

		}

		/**
		 * WordPress `parse_request` action hook callback function.
		 *
		 * Allows for us to implement our own hook to parse the request.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param \WP $query The main WP class.
		 */
		public function action_parse_request( \WP $query ) {

			/**
			 * Fires once all query variables for the current request have been
			 * parsed in the main WordPress query.
			 *
			 * @since 1.0.0
			 *
			 * @param \WP $query The main WP class.
			 */
			do_action( 'nomad/virtual_pages/parse_request', $query );

		}

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return Nomad_Virtual_Pages An instance of this class.
		 */
		public static function instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * Set up all necessary WordPress hooks and filters.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'add_rewrite_rules' ) );
			add_filter( 'query_vars', array( $this, 'filter_query_vars' ) );
			add_filter( 'template_include', array( $this, 'filter_template_include' ) );
			add_action( 'parse_request', array( $this, 'action_parse_request' ) );

		}

	}

	Nomad_Virtual_Pages::instance();

}
