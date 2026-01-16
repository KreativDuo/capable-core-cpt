<?php
/**
 * Custom Post Type Registration
 *
 * This class handles the registration of custom post types required by the theme,
 * specifically the unified 'Layout' CPT.
 *
 * @package   CapCore_Starter
 * @author    Capable Themes
 * @license   GPLv2 or later
 * @link      https://capable-themes.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the 'capcore_layout' custom post type and its related UI enhancements.
 */
class Capable_Core_PostTypes {

	/**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private string $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private string $version;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The required post type slug.
     */
    private string $post_type_slug;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @param string $post_type_slug The version of this plugin.
     * @since    1.0.0
     */
    public function __construct(string $plugin_name, string $version, string $post_type_slug )
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->post_type_slug = $post_type_slug;

    }

	/**
	 * Registers the unified 'Layout' Custom Post Type.
	 *
	 * @return void
	 */
	public function register_layout_cpt(): void {
		$labels = array(
			'name'                  => _x( 'Layouts', 'Post Type General Name', 'capcore-starter' ),
			'singular_name'         => _x( 'Layout', 'Post Type Singular Name', 'capcore-starter' ),
			'menu_name'             => __( 'Layouts', 'capcore-starter' ),
			'name_admin_bar'        => __( 'Layout', 'capcore-starter' ),
			'archives'              => __( 'Layout Archives', 'capcore-starter' ),
			'attributes'            => __( 'Layout Attributes', 'capcore-starter' ),
			'parent_item_colon'     => __( 'Parent Layout:', 'capcore-starter' ),
			'all_items'             => __( 'All Layouts', 'capcore-starter' ),
			'add_new_item'          => __( 'Add New Layout', 'capcore-starter' ),
			'add_new'               => __( 'Add New', 'capcore-starter' ),
			'new_item'              => __( 'New Layout', 'capcore-starter' ),
			'edit_item'             => __( 'Edit Layout', 'capcore-starter' ),
			'update_item'           => __( 'Update Layout', 'capcore-starter' ),
			'view_item'             => __( 'View Layout', 'capcore-starter' ),
			'view_items'            => __( 'View Layouts', 'capcore-starter' ),
			'search_items'          => __( 'Search Layout', 'capcore-starter' ),
		);

		$args = array(
			'label'                 => __( 'Layout', 'capcore-starter' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'elementor' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-layout',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
		);

		register_post_type( $this->post_type_slug, $args );

	}

	/**
	 * Adds submenus for each layout type under the main 'Layouts' menu.
	 *
	 * @return void
	 */
	public function add_layout_submenus(): void {
		$base_url = 'edit.php?post_type=' . $this->post_type_slug;

		add_submenu_page( $base_url, __( 'Headers', 'capcore-starter' ), __( 'Headers', 'capcore-starter' ), 'edit_posts', $base_url . '&layout_type=header' );
		add_submenu_page( $base_url, __( 'Footers', 'capcore-starter' ), __( 'Footers', 'capcore-starter' ), 'edit_posts', $base_url . '&layout_type=footer' );
		add_submenu_page( $base_url, __( 'Custom Layouts', 'capcore-starter' ), __( 'Custom', 'capcore-starter' ), 'edit_posts', $base_url . '&layout_type=custom' );
	}

	/**
	 * Adds a dropdown filter for layout types on the CPT admin list table.
	 *
	 * @param string $post_type The current post type.
	 * @return void
	 */
	public function add_layout_type_filter_dropdown( string $post_type ): void {
		if ( $this->post_type_slug !== $post_type ) {
			return;
		}

		$current_filter = $_GET['layout_type'] ?? '';
		$layout_types   = array(
			'header' => __( 'Headers', 'capcore-starter' ),
			'footer' => __( 'Footers', 'capcore-starter' ),
			'custom' => __( 'Custom', 'capcore-starter' ),
		);
		?>
		<select name="layout_type">
			<option value=""><?php esc_html_e( 'All Layout Types', 'capcore-starter' ); ?></option>
			<?php foreach ( $layout_types as $slug => $name ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $current_filter, $slug ); ?>>
					<?php echo esc_html( $name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Modifies the main query on the admin list table to filter by layout type.
	 *
	 * @param \WP_Query $query The WP_Query instance.
	 * @return void
	 */
	public function filter_layouts_by_type_in_admin_list( \WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || 'edit-' . $this->post_type_slug !== $screen->id ) {
			return;
		}

		$layout_type = isset( $_GET['layout_type'] ) ? sanitize_text_field( wp_unslash( $_GET['layout_type'] ) ) : '';

		if ( ! empty( $layout_type ) ) {
			$query->set( 'meta_key', '_capcore_layout_type' );
			$query->set( 'meta_value', $layout_type );
		}
	}

	/**
	 * Adds the 'Type' column header to the admin list table.
	 *
	 * @param array $columns The existing array of column headers.
	 * @return array The modified array of column headers.
	 */
	public function add_layout_type_admin_column_header( array $columns ): array {
		$new_columns = array();
		foreach ( $columns as $key => $title ) {
			$new_columns[ $key ] = $title;
			if ( 'title' === $key ) {
				$new_columns['layout_type'] = __( 'Type', 'capcore-starter' );
			}
		}
		return $new_columns;
	}

	/**
	 * Renders the content for the custom 'Type' column.
	 *
	 * @param string $column_name The key of the current column.
	 * @param int    $post_id     The ID of the current post.
	 * @return void
	 */
	public function render_layout_type_admin_column_content( string $column_name, int $post_id ): void {
		if ( 'layout_type' === $column_name ) {
			$layout_type = get_post_meta( $post_id, '_capcore_layout_type', true );
			if ( ! empty( $layout_type ) ) {
				echo esc_html( ucfirst( $layout_type ) );
			} else {
				echo '—'; // Display a dash if no type is set.
			}
		}
	}

	/**
	 * Adds the meta box container for selecting the layout type.
	 *
	 * @return void
	 */
	public function add_layout_type_meta_box(): void {
		add_meta_box(
			'capcore_layout_type_box',
			__( 'Layout Type', 'capcore-starter' ),
			array( $this, 'render_layout_type_meta_box' ),
			$this->post_type_slug,
			'side',
			'high'
		);
	}

	/**
	 * Renders the HTML content for the layout type meta box.
	 *
	 * @param \WP_Post $post The current post object.
	 * @return void
	 */
	public function render_layout_type_meta_box( \WP_Post $post ): void {
		wp_nonce_field( 'capcore_save_layout_type', 'capcore_layout_type_nonce' );
		$current_value = get_post_meta( $post->ID, '_capcore_layout_type', true );
		?>
		<p>
			<label for="capcore_layout_type_field"><?php esc_html_e( 'Select the type for this layout:', 'capcore-starter' ); ?></label>
			<br>
			<select name="capcore_layout_type" id="capcore_layout_type_field" class="widefat">
				<option value="" <?php selected( $current_value, '' ); ?>><?php esc_html_e( '-- Select Type --', 'capcore-starter' ); ?></option>
				<option value="header" <?php selected( $current_value, 'header' ); ?>><?php esc_html_e( 'Header', 'capcore-starter' ); ?></option>
				<option value="footer" <?php selected( $current_value, 'footer' ); ?>><?php esc_html_e( 'Footer', 'capcore-starter' ); ?></option>
				<option value="custom" <?php selected( $current_value, 'custom' ); ?>><?php esc_html_e( 'Custom', 'capcore-starter' ); ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Saves the custom meta data when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @return void
	 */
	public function save_layout_type_meta_box( int $post_id ): void {
		if ( ! isset( $_POST['capcore_layout_type_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['capcore_layout_type_nonce'] ), 'capcore_save_layout_type' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( isset( $_POST['capcore_layout_type'] ) ) {
			$new_value = sanitize_text_field( wp_unslash( $_POST['capcore_layout_type'] ) );
			update_post_meta( $post_id, '_capcore_layout_type', $new_value );
		}
	}

    /**
	 * Locates the appropriate template file for the 'capcore_layout' CPT.
	 *
	 * This function checks the theme/child theme directories first for a
	 * 'single-capcore_layout.php'. If not found, it falls back to the
	 * template included with the plugin.
	 *
	 * @param string $template The path of the template to include.
	 * @return string The path of the new template file or the original.
	 */
	public function locate_plugin_template( string $template ): string {
		// Only apply our logic to the 'capcore_layout' CPT on a single view.
		if ( is_singular( 'capcore_layout' ) ) {

			// 1. Check the theme hierarchy first.
			// locate_template() automatically checks the child theme first, then the parent.
			$theme_template = locate_template( array( 'single-capcore_layout.php' ) );

			// If the theme has a template, let it win.
			if ( ! empty( $theme_template ) ) {
				return $theme_template;
			}

			// 2. If the theme has no template, use our plugin's fallback.
			$plugin_template = plugin_dir_path(dirname(__FILE__)) . 'templates/canvas-template.php';

			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		// For all other cases, return the original template.
		return $template;

	}

}