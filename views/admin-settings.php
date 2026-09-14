<?php
/**
 * Admin settings page template
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap editor-tweaks-for-codemirror-settings">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<div class="editor-tweaks-for-codemirror-layout">
		<form action="options.php" method="post" class="editor-tweaks-for-codemirror-form">
			<?php
			settings_fields( 'editor_tweaks_for_codemirror_settings' );
			do_settings_sections( 'editor-tweaks-for-codemirror' );
			submit_button( __( 'Save Settings', 'editor-tweaks-for-codemirror' ) );
			?>
		</form>

		<div class="editor-tweaks-for-codemirror-preview">
			<div class="editor-tweaks-for-codemirror-preview-header">
				<h2><?php esc_html_e( 'Preview', 'editor-tweaks-for-codemirror' ); ?></h2>
				<label for="editor-tweaks-for-codemirror-preview-file-type" class="screen-reader-text">
					<?php esc_html_e( 'Preview File Type:', 'editor-tweaks-for-codemirror' ); ?>
				</label>
				<select id="editor-tweaks-for-codemirror-preview-file-type">
					<option value="javascript">JavaScript</option>
					<option value="php">PHP</option>
					<option value="css">CSS</option>
					<option value="html">HTML</option>
					<option value="json">JSON</option>
				</select>
			</div>
			<p class="description">
				<?php esc_html_e( 'Changes are applied to every CodeMirror editor in WordPress once saved.', 'editor-tweaks-for-codemirror' ); ?>
			</p>
			<textarea id="editor-tweaks-for-codemirror-preview-editor" class="editor-tweaks-for-codemirror-preview-textarea" data-mode="javascript">
				<?php
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				echo esc_textarea( file_get_contents( EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'sample-data/example.js' ) );
				?>
			</textarea>
		</div>
	</div>
</div>
