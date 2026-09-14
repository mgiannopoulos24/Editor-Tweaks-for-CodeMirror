<?php
/**
 * Autoloader for Editor Tweaks for CodeMirror plugin
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Editor_Tweaks_For_CodeMirror_Autoloader class
 *
 * @package Editor_Tweaks_For_CodeMirror
 */
class Editor_Tweaks_For_CodeMirror_Autoloader {

	/**
	 * Register autoloader
	 */
	public static function register() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoload classes
	 *
	 * @param string $class_name Class name to load.
	 */
	public static function autoload( $class_name ) {
		/**
		 * Only load our classes
		 */
		if ( strpos( $class_name, 'Editor_Tweaks_For_CodeMirror' ) !== 0 ) {
			return;
		}

		/**
		 * Convert class name to file name.
		 *
		 * Maps Editor_Tweaks_For_CodeMirror_Admin to
		 * includes/class-editor-tweaks-for-codemirror-admin.php.
		 */
		$file_name = str_replace( 'Editor_Tweaks_For_CodeMirror_', '', $class_name );
		$file_name = str_replace( '_', '-', $file_name );
		$file_name = strtolower( $file_name );
		$file_path = EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'includes/class-editor-tweaks-for-codemirror-' . $file_name . '.php';

		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
	}
}
