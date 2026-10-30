<?php
/**
 * Admin settings page template
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$editor_tweaks_for_codemirror_base_url = menu_page_url( 'editor-tweaks-for-codemirror', false );
?>

<div class="wrap editor-tweaks-for-codemirror-settings">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<nav class="nav-tab-wrapper">
		<a href="<?php echo esc_url( add_query_arg( 'scope', 'user', $editor_tweaks_for_codemirror_base_url ) ); ?>" class="nav-tab <?php echo 'user' === $scope ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-admin-users" aria-hidden="true"></span>
			<?php esc_html_e( 'My Preferences', 'editor-tweaks-for-codemirror' ); ?>
		</a>
		<a href="<?php echo esc_url( add_query_arg( 'scope', 'site', $editor_tweaks_for_codemirror_base_url ) ); ?>" class="nav-tab <?php echo 'site' === $scope ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-admin-site-alt3" aria-hidden="true"></span>
			<?php esc_html_e( 'Site Defaults', 'editor-tweaks-for-codemirror' ); ?>
		</a>
	</nav>

	<p class="description">
		<?php if ( 'site' === $scope ) : ?>
			<?php esc_html_e( 'These defaults apply to every user who has not saved their own preferences.', 'editor-tweaks-for-codemirror' ); ?>
		<?php else : ?>
			<?php esc_html_e( 'These preferences apply only to your account. Until you save, you see the site defaults.', 'editor-tweaks-for-codemirror' ); ?>
		<?php endif; ?>
	</p>

	<div class="editor-tweaks-for-codemirror-layout">
		<form action="<?php echo esc_url( 'site' === $scope ? admin_url( 'options.php' ) : admin_url( 'admin-post.php' ) ); ?>" method="post" class="editor-tweaks-for-codemirror-form">
			<?php if ( 'site' === $scope ) : ?>
				<?php settings_fields( 'editor_tweaks_for_codemirror_settings' ); ?>
			<?php else : ?>
				<input type="hidden" name="action" value="editor_tweaks_for_codemirror_save_user_preferences">
				<?php wp_nonce_field( 'editor_tweaks_for_codemirror_user_preferences' ); ?>
			<?php endif; ?>
			<?php
			do_settings_sections( 'editor-tweaks-for-codemirror' );
			?>
			<div class="editor-tweaks-for-codemirror-submit-wrap">
				<?php
				submit_button(
					'site' === $scope ? __( 'Save Site Defaults', 'editor-tweaks-for-codemirror' ) : __( 'Save Preferences', 'editor-tweaks-for-codemirror' ),
					'primary',
					'submit',
					false
				);
				?>
				<?php if ( 'user' === $scope && ! empty( Editor_Tweaks_For_CodeMirror_Editor::get_user_options() ) ) : ?>
					<button type="submit" name="editor_tweaks_for_codemirror_reset_preferences" value="1" class="button button-secondary">
						<?php esc_html_e( 'Reset to Site Defaults', 'editor-tweaks-for-codemirror' ); ?>
					</button>
				<?php endif; ?>
			</div>
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
				<?php if ( 'site' === $scope ) : ?>
					<?php esc_html_e( 'Changes are applied to every CodeMirror editor in WordPress once saved.', 'editor-tweaks-for-codemirror' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'Changes are applied to your CodeMirror editors once saved.', 'editor-tweaks-for-codemirror' ); ?>
				<?php endif; ?>
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
