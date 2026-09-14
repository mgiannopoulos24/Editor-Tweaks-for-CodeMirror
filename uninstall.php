<?php
/**
 * Uninstall procedure for Editor Tweaks for CodeMirror.
 *
 * Removes all plugin data when the plugin is uninstalled.
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'editor_tweaks_for_codemirror_options' );
