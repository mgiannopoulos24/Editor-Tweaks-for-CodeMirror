<?php
/**
 * Main plugin class
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Editor_Tweaks_For_CodeMirror class
 *
 * @package Editor_Tweaks_For_CodeMirror
 */
class Editor_Tweaks_For_CodeMirror {

	/**
	 * Plugin instance
	 *
	 * @var Editor_Tweaks_For_CodeMirror
	 */
	private static $instance = null;

	/**
	 * Admin instance
	 *
	 * @var Editor_Tweaks_For_CodeMirror_Admin
	 */
	public $admin;

	/**
	 * Editor instance
	 *
	 * @var Editor_Tweaks_For_CodeMirror_Editor
	 */
	public $editor;

	/**
	 * Get plugin instance
	 *
	 * @return Editor_Tweaks_For_CodeMirror
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Initialize plugin
	 */
	private function init() {
		/**
		 * Load text domain
		 */
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 1 );

		/**
		 * Initialize admin
		 */
		if ( is_admin() ) {
			require_once EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'includes/class-editor-tweaks-for-codemirror-admin.php';
			$this->admin = new Editor_Tweaks_For_CodeMirror_Admin();
		}

		/**
		 * Initialize editor modifications
		 */
		require_once EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'includes/class-editor-tweaks-for-codemirror-editor.php';
		$this->editor = new Editor_Tweaks_For_CodeMirror_Editor();
	}

	/**
	 * Load plugin text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'editor-tweaks-for-codemirror',
			false,
			dirname( EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_BASENAME ) . '/languages'
		);
	}
}
