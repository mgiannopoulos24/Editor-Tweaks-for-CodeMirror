<?php
/**
 * Plugin Name: Editor Tweaks for CodeMirror
 * Plugin URI: https://github.com/mgiannopoulos24/editor-tweaks-for-codemirror
 * Description: Tweak every CodeMirror editor in WordPress with custom themes, fonts, and display options.
 * Version: 1.0.0
 * Requires at least: 6.5
 * Tested up to:      7.1
 * Requires PHP:      8.1
 * Author: Marios Giannopoulos
 * Author URI: https://github.com/mgiannopoulos24
 * License: GNU General Public License v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: editor-tweaks-for-codemirror
 * Domain Path: /languages
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define plugin constants
 */
define( 'EDITOR_TWEAKS_FOR_CODEMIRROR_VERSION', '1.0.0' );
define( 'EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoloader
 */
require_once EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'includes/class-editor-tweaks-for-codemirror-autoloader.php';
Editor_Tweaks_For_CodeMirror_Autoloader::register();

/**
 * Initialize the plugin
 */
require_once EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'includes/class-editor-tweaks-for-codemirror.php';

/**
 * Get the main plugin instance
 *
 * @return Editor_Tweaks_For_CodeMirror
 */
function editor_tweaks_for_codemirror() {
	return Editor_Tweaks_For_CodeMirror::get_instance();
}

/**
 * Initialize the plugin
 */
editor_tweaks_for_codemirror();
