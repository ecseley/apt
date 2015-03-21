<?php
/**
 * AMP Styles
 */

class AMPStyles {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.4';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'amp-styles';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

        /********************
         * System hooks
         ********************/
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'remove_ga_widget' ) );
		add_action( 'admin_menu', array( $this, 'remove_ga_menu' ), 100 );

        /********************
         * Public UI hooks
         ********************/
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'login_head', array( $this, 'login_head') );

        if ( is_admin() ) {

            /********************
             * Admin UI hooks
             ********************/
            // Add the options page and menu item.
            // add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
            // Load admin style sheet and JavaScript.
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ), 100 );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
            add_action( 'wp_dashboard_setup', array( $this, 'enqueue_onboarding_dependencies' ) );
            // registered_taxonomy runs after a taxonomy has been registered.  this will make changes to the taxonomy in Themify
        	add_action( 'registered_taxonomy', array( $this, 'change_themify_tax_labels' ) );
        }

	} // end __construct

    public function login_head() {

        echo "<link rel='stylesheet' type='text/css' media='all' href='" . plugins_url( 'css/admin.css', __FILE__ ) . "' />";

    } // end login_head

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    AMPStyles    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	} // end get_instance

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		// TODO: Define activation functionality here

	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		// TODO: Define deactivation functionality here

	} // end deactivate

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end load_plugin_textdomain

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

        wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), $this->version );

        $userId = get_current_user_id();
        if ( ! is_super_admin( $userId ) ) {
            wp_enqueue_style( $this->plugin_slug .'-nonsuperuser-admin-styles', plugins_url( 'css/nonSuperUserAdmin.css', __FILE__ ), false, $this->version );
        }

        wp_dequeue_style('themify-builder-main');

	} // end enqueue_admin_styles

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

        wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );

	} // end enqueue_admin_scripts

	// Register dependencies for onboarding Modal
	public function enqueue_onboarding_dependencies() {
		$dependencies = array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' );
		wp_enqueue_script( 'amp-onboarding-dependencies', plugins_url( 'js/admin.js', __FILE__ ), $dependencies, $this->version );
		wp_enqueue_script( 'amp-onboarding-carousel', plugins_url( 'js/jquery.slides.min.js', __FILE__ ), array('jquery'), $this->version );
	} // end enqueue_onboarding_dependencies

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), false, $this->version );

        $userId = get_current_user_id();
        if ( $userId && ! is_super_admin( $userId ) ) {
            wp_enqueue_style( $this->plugin_slug .'-nonsuperuser-plugin-styles', plugins_url( 'css/nonSuperUserPublic.css', __FILE__ ), $this->version );
        }

	} // end enqueue_styles

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );

	} // end enqueue_scripts

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'plugin-name' to the name of your plugin
		 */
		$this->plugin_screen_hook_suffix = add_plugins_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	} // end add_plugin_admin_menu

	/**
	 * Changes Themify Custom Post Type Taxonomy Labels to be Consistant with Amp
	 *
	 *
	 **/
	public function change_themify_tax_labels($taxonomy) {

	    if ( post_type_exists( 'slider' ) ) {
		// check taxonomy register
			if ( taxonomy_exists( 'slider-category' ) ) {
				$tax_object = get_taxonomy( 'slider-category' );
				$labels = get_taxonomy_labels( $tax_object );
				$labels->name = "Categories";
				$labels->singular_name = "Category";
				$labels->search_items = "Search Categories";
				$labels->popular_items = "Popular Categories";
				$labels->parent_item = "Parent Category";
				$labels->parent_item_colon = "Parent Category:";
				$labels->edit_item = "Edit Category";
				$labels->update_item = "Update Category";
				$labels->add_new_item = "Add New Category";
				$labels->new_item_name = "New Category";
				$labels->separate_items_with_commas = "Seperate Categories with commas";
				$labels->add_or_remove_items = "Add or Remove Category";
				$labels->choose_from_most_used = "Choose from most used Categories";
				$labels->menu_name = "Slider Categories";
				$labels->name_admin_bar = "Slider Categories";

				$tax_object->labels = $labels;

				//Add in handling for the uncategorised category
				require_once( ABSPATH . WPINC . "/amp-categories.php" );
				$slider_categories = new AMPCategories( 'slider', 'slider-category' );
				$slider_categories->force_uncategorised_category();
			}
		} //end slider cpt rewrite labels

		if ( post_type_exists( 'highlight' ) ) {
		// check taxonomy register
			if ( taxonomy_exists( 'highlight-category' ) ) {
				$tax_object = get_taxonomy( 'highlight-category' );
				$labels = get_taxonomy_labels( $tax_object );
				$labels->name = "Categories";
				$labels->singular_name = "Category";
				$labels->search_items = "Search Categories";
				$labels->popular_items = "Popular Categories";
				$labels->parent_item = "Parent Category";
				$labels->parent_item_colon = "Parent Category:";
				$labels->edit_item = "Edit Category";
				$labels->update_item = "Update Category";
				$labels->add_new_item = "Add New Category";
				$labels->new_item_name = "New Category";
				$labels->separate_items_with_commas = "Seperate Categories with commas";
				$labels->add_or_remove_items = "Add or Remove Category";
				$labels->choose_from_most_used = "Choose from most used Categories";
				$labels->menu_name = "Highlight Categories";
				$labels->name_admin_bar = "Highlight Categories";

				$tax_object->labels = $labels;

				//Add in handling for the uncategorised category
				require_once( ABSPATH . WPINC . "/amp-categories.php" );
				$highlight_categories = new AMPCategories( 'highlight', 'highlight-category' );
				$highlight_categories->force_uncategorised_category();
			}
		} //end highlight cpt rewrite labels

		if ( post_type_exists( 'portfolio' ) ) {
		// check taxonomy register
			if ( taxonomy_exists( 'portfolio-category' ) ) {
				$tax_object = get_taxonomy( 'portfolio-category' );
				$labels = get_taxonomy_labels( $tax_object );
				$labels->name = "Categories";
				$labels->singular_name = "Category";
				$labels->search_items = "Search Categories";
				$labels->popular_items = "Popular Categories";
				$labels->parent_item = "Parent Category";
				$labels->parent_item_colon = "Parent Category:";
				$labels->edit_item = "Edit Category";
				$labels->update_item = "Update Category";
				$labels->add_new_item = "Add New Category";
				$labels->new_item_name = "New Category";
				$labels->separate_items_with_commas = "Seperate Categories with commas";
				$labels->add_or_remove_items = "Add or Remove Category";
				$labels->choose_from_most_used = "Choose from most used Categories";
				$labels->menu_name = "Portfolio Categories";
				$labels->name_admin_bar = "Portfolio Categories";

				$tax_object->labels = $labels;

				//Add in handling for the uncategorised category
				require_once( ABSPATH . WPINC . "/amp-categories.php" );
				$portfolio_categories = new AMPCategories( 'portfolio', 'portfolio-category' );
				$portfolio_categories->force_uncategorised_category();
			}
		} //end portfolio cpt rewrite labels

		if ( post_type_exists( 'testimonial' ) ) {
		// check taxonomy register
			if ( taxonomy_exists( 'testimonial-category' ) ) {
				$tax_object = get_taxonomy( 'testimonial-category' );
				$labels = get_taxonomy_labels( $tax_object );
				$labels->name = "Categories";
				$labels->singular_name = "Category";
				$labels->search_items = "Search Categories";
				$labels->popular_items = "Popular Categories";
				$labels->parent_item = "Parent Category";
				$labels->parent_item_colon = "Parent Category:";
				$labels->edit_item = "Edit Category";
				$labels->update_item = "Update Category";
				$labels->add_new_item = "Add New Category";
				$labels->new_item_name = "New Category";
				$labels->separate_items_with_commas = "Seperate Categories with commas";
				$labels->add_or_remove_items = "Add or Remove Category";
				$labels->choose_from_most_used = "Choose from most used Categories";
				$labels->menu_name = "Testimonial Categories";
				$labels->name_admin_bar = "Testimonial Categories";

				$tax_object->labels = $labels;

				//Add in handling for the uncategorised category
				require_once( ABSPATH . WPINC . "/amp-categories.php" );
				$testimonial_categories = new AMPCategories( 'testimonial', 'testimonial-category' );
				$testimonial_categories->force_uncategorised_category();
			}
		} //end testimonial cpt rewrite labels

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {

		include_once( 'views/admin.php' );

	} // end display_plugin_admin_page

	/**
	 * Remove the Google Analytics dashboard widget
	 *
	 */
	public function remove_ga_widget() {
		remove_meta_box('google_analytics_dashboard', 'dashboard', 'side');
	} // end remove_ga_widget

	/**
	 * Remove the Google Analytics dashboard menu item
	 *
	 */
	public function remove_ga_menu() {
		remove_submenu_page( 'index.php', 'google-analytics-statistics' );
	}// end remove_ga_menu

}