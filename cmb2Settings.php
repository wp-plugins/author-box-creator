<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class author_box_creator_Admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'author_box_creator_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'author_box_creator_option_metabox';

	/**
	 * Array of metaboxes/fields
	 * @var array
	 */
	protected $option_metabox = array();

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Author Box Creator', 'author_box_creator' );
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
		add_filter('the_content', array( $this, 'add_author_box'));
		add_action( 'wp_footer', array($this, 'enqueue_styles'));
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2_options_page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<div><p><a href="http://www.thinklandingpages.com/author_box_creator/">Get the Author Box Creator Quick Start Guide here.</a></p>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 * @param  array $meta_boxes
	 * @return array $meta_boxes
	 */
	function add_options_page_metabox() {

		$cmb = new_cmb2_box( array(
			'id'      => $this->metabox_id,
			'hookup'  => false,
			'show_on' => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields

		$cmb->add_field( array(
			'name' => __( 'Background Color', 'author_box_creator' ),
			//'desc' => __( 'field description (optional)', 'author_box_creator' ),
			'id'   => 'background_color',
			'type' => 'colorpicker',
			'default' => '#ffffff',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Title', 'author_box_creator' ),
			//'desc'    => __( 'field description (optional)', 'author_box_creator' ),
			'id'      => 'title',
			'type'    => 'text',
			'default' => 'About the author',
		) );

	}

	/**
	 * Defines the theme option metabox and field configuration
	 * @since  0.1.0
	 * @return array
	 */
	public function option_metabox() {
		return ;
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'fields', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}
	
	function add_author_box($content) {
		if( is_singular() && is_main_query() ) {
			$dir = plugin_dir_path( __FILE__ );
			ob_start();
			include $dir.'template/authorBoxCreatorTemplate.php';
			$new_content = ob_get_clean();
			$content .= $new_content;	
		}	
		return $content;
	}

	function enqueue_styles(){
		wp_register_style( 'author-box-creator-css', plugin_dir_url(__FILE__).'css/authorBoxCreator.css' );
		wp_enqueue_style('author-box-creator-css');
	}


}

// Get it started
$GLOBALS['author_box_creator_Admin'] = new author_box_creator_Admin();
$GLOBALS['author_box_creator_Admin']->hooks();

/**
 * Helper function to get/return the author_box_creator_Admin object
 * @since  0.1.0
 * @return author_box_creator_Admin object
 */
function author_box_creator_Admin() {
	global $author_box_creator_Admin;
	return $author_box_creator_Admin;
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function author_box_creator_get_option( $key = '' ) {
	global $author_box_creator_Admin;
	return cmb2_get_option( $author_box_creator_Admin->key, $key );
	
}
