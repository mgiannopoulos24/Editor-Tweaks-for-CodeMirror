<?php
/**
 * Admin functionality
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Editor_Tweaks_For_CodeMirror_Admin class
 *
 * @package Editor_Tweaks_For_CodeMirror
 */
class Editor_Tweaks_For_CodeMirror_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'Editor Tweaks for CodeMirror', 'editor-tweaks-for-codemirror' ),
			__( 'Editor Tweaks for CodeMirror', 'editor-tweaks-for-codemirror' ),
			'manage_options',
			'editor-tweaks-for-codemirror',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting(
			'editor_tweaks_for_codemirror_settings',
			'editor_tweaks_for_codemirror_options',
			array(
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'capability'        => 'manage_options',
			)
		);

		/**
		 * Theme section
		 */
		add_settings_section(
			'editor_tweaks_for_codemirror_theme_section',
			__( 'Editor Theme', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_theme_section' ),
			'editor-tweaks-for-codemirror'
		);

		add_settings_field(
			'theme',
			__( 'Theme', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_theme_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_theme_section'
		);

		/**
		 * Font size section
		 */
		add_settings_section(
			'editor_tweaks_for_codemirror_font_section',
			__( 'Font Settings', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_font_section' ),
			'editor-tweaks-for-codemirror'
		);

		add_settings_field(
			'font_family',
			__( 'Font Family', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_font_family_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_font_section'
		);

		add_settings_field(
			'font_weight',
			__( 'Font Weight', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_font_weight_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_font_section'
		);

		add_settings_field(
			'font_size',
			__( 'Font Size', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_font_size_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_font_section'
		);

		add_settings_field(
			'line_height',
			__( 'Line Height', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_line_height_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_font_section'
		);

		add_settings_field(
			'letter_spacing',
			__( 'Letter Spacing', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_letter_spacing_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_font_section'
		);

		/**
		 * Line numbers section
		 */
		add_settings_section(
			'editor_tweaks_for_codemirror_display_section',
			__( 'Display Options', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_display_section' ),
			'editor-tweaks-for-codemirror'
		);

		add_settings_field(
			'line_numbers',
			__( 'Show Line Numbers', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_line_numbers_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_display_section'
		);

		add_settings_field(
			'word_wrap',
			__( 'Word Wrap', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_word_wrap_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_display_section'
		);

		add_settings_field(
			'ruler_column',
			__( 'Ruler Column', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_ruler_column_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_display_section'
		);

		add_settings_field(
			'current_line_highlight',
			__( 'Highlight Current Line', 'editor-tweaks-for-codemirror' ),
			array( $this, 'render_current_line_highlight_field' ),
			'editor-tweaks-for-codemirror',
			'editor_tweaks_for_codemirror_display_section'
		);
	}

	/**
	 * Sanitize settings
	 *
	 * @param array $input Settings input.
	 * @return array Sanitized settings.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = array();

		if ( isset( $input['theme'] ) ) {
			$sanitized['theme'] = sanitize_text_field( $input['theme'] );
		}

		if ( isset( $input['font_family'] ) ) {
			$sanitized['font_family'] = sanitize_text_field( $input['font_family'] );
		}

		if ( isset( $input['font_weight'] ) ) {
			$font_weight = sanitize_text_field( $input['font_weight'] );
			if ( in_array( $font_weight, array( '100', '200', '300', '400', '500', '600', '700', '800', '900' ), true ) ) {
				$sanitized['font_weight'] = $font_weight;
			}
		}

		if ( isset( $input['font_size'] ) ) {
			$sanitized['font_size'] = absint( $input['font_size'] );
		}

		if ( isset( $input['line_height'] ) ) {
			$line_height = trim( $input['line_height'] );
			/**
			 * Allow float values (1.5) or values with units (1.5em, 24px, etc.)
			 */
			if ( preg_match( '/^(\d+\.?\d*)\s*(em|px|rem)?$/i', $line_height, $matches ) ) {
				$sanitized['line_height'] = $line_height;
			} else {
				/**
				 * Fallback to default if invalid format
				 */
				$sanitized['line_height'] = '1.5';
			}
		}

		if ( isset( $input['letter_spacing'] ) ) {
			$letter_spacing = trim( $input['letter_spacing'] );
			/**
			 * Allow integer or float values in pixels
			 */
			if ( preg_match( '/^-?\d+\.?\d*$/', $letter_spacing ) ) {
				$sanitized['letter_spacing'] = floatval( $letter_spacing );
			} else {
				/**
				 * Fallback to 0 if invalid format
				 */
				$sanitized['letter_spacing'] = 0;
			}
		}

		if ( isset( $input['ruler_column'] ) ) {
			$sanitized['ruler_column'] = absint( $input['ruler_column'] );
		}

		if ( isset( $input['line_numbers'] ) ) {
			$sanitized['line_numbers'] = ! empty( $input['line_numbers'] );
		}

		if ( isset( $input['word_wrap'] ) ) {
			$sanitized['word_wrap'] = ! empty( $input['word_wrap'] );
		}

		if ( isset( $input['current_line_highlight'] ) ) {
			$sanitized['current_line_highlight'] = ! empty( $input['current_line_highlight'] );
		}

		return $sanitized;
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		/**
		 * Check user capabilities
		 */
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'editor-tweaks-for-codemirror' ) );
		}

		include EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'views/admin-settings.php';
	}

	/**
	 * Render theme section
	 */
	public function render_theme_section() {
		echo '<p>' . esc_html__( 'Choose the theme for the CodeMirror editor.', 'editor-tweaks-for-codemirror' ) . '</p>';
	}

	/**
	 * Render theme field
	 */
	public function render_theme_field() {
		$options = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$theme   = isset( $options['theme'] ) ? $options['theme'] : 'default';
		$themes  = array(
			'default'                 => __( 'Default', 'editor-tweaks-for-codemirror' ),
			/**
			 * Popular Dark Themes
			 */
			'dark'                    => __( 'Dark (Monokai)', 'editor-tweaks-for-codemirror' ),
			'monokai'                 => __( 'Monokai', 'editor-tweaks-for-codemirror' ),
			'dracula'                 => __( 'Dracula', 'editor-tweaks-for-codemirror' ),
			'material'                => __( 'Material', 'editor-tweaks-for-codemirror' ),
			'material-darker'         => __( 'Material Darker', 'editor-tweaks-for-codemirror' ),
			'material-ocean'          => __( 'Material Ocean', 'editor-tweaks-for-codemirror' ),
			'material-palenight'      => __( 'Material Palenight', 'editor-tweaks-for-codemirror' ),
			'solarized-dark'          => __( 'Solarized Dark', 'editor-tweaks-for-codemirror' ),
			'nord'                    => __( 'Nord', 'editor-tweaks-for-codemirror' ),
			'github-dark'             => __( 'GitHub Dark', 'editor-tweaks-for-codemirror' ),
			'ambiance'                => __( 'Ambiance', 'editor-tweaks-for-codemirror' ),
			'base16-dark'             => __( 'Base16 Dark', 'editor-tweaks-for-codemirror' ),
			'blackboard'              => __( 'Blackboard', 'editor-tweaks-for-codemirror' ),
			'cobalt'                  => __( 'Cobalt', 'editor-tweaks-for-codemirror' ),
			'erlang-dark'             => __( 'Erlang Dark', 'editor-tweaks-for-codemirror' ),
			'hopscotch'               => __( 'Hopscotch', 'editor-tweaks-for-codemirror' ),
			'lesser-dark'             => __( 'Lesser Dark', 'editor-tweaks-for-codemirror' ),
			'mbo'                     => __( 'MBO', 'editor-tweaks-for-codemirror' ),
			'midnight'                => __( 'Midnight', 'editor-tweaks-for-codemirror' ),
			'neat'                    => __( 'Neat', 'editor-tweaks-for-codemirror' ),
			'night'                   => __( 'Night', 'editor-tweaks-for-codemirror' ),
			'oceanic-next'            => __( 'Oceanic Next', 'editor-tweaks-for-codemirror' ),
			'panda-syntax'            => __( 'Panda Syntax', 'editor-tweaks-for-codemirror' ),
			'paraiso-dark'            => __( 'Paraiso Dark', 'editor-tweaks-for-codemirror' ),
			'pastel-on-dark'          => __( 'Pastel on Dark', 'editor-tweaks-for-codemirror' ),
			'railscasts'              => __( 'RailsCasts', 'editor-tweaks-for-codemirror' ),
			'rubyblue'                => __( 'Ruby Blue', 'editor-tweaks-for-codemirror' ),
			'seti'                    => __( 'Seti', 'editor-tweaks-for-codemirror' ),
			'the-matrix'              => __( 'The Matrix', 'editor-tweaks-for-codemirror' ),
			'tomorrow-night-bright'   => __( 'Tomorrow Night Bright', 'editor-tweaks-for-codemirror' ),
			'tomorrow-night-eighties' => __( 'Tomorrow Night Eighties', 'editor-tweaks-for-codemirror' ),
			'twilight'                => __( 'Twilight', 'editor-tweaks-for-codemirror' ),
			'vibrant-ink'             => __( 'Vibrant Ink', 'editor-tweaks-for-codemirror' ),
			'xq-dark'                 => __( 'XQ Dark', 'editor-tweaks-for-codemirror' ),
			'yeti'                    => __( 'Yeti', 'editor-tweaks-for-codemirror' ),
			'zenburn'                 => __( 'Zenburn', 'editor-tweaks-for-codemirror' ),
			/**
			 * Light Themes
			 */
			'solarized'               => __( 'Solarized Light', 'editor-tweaks-for-codemirror' ),
			'github-light'            => __( 'GitHub Light', 'editor-tweaks-for-codemirror' ),
			'base16-light'            => __( 'Base16 Light', 'editor-tweaks-for-codemirror' ),
			'eclipse'                 => __( 'Eclipse', 'editor-tweaks-for-codemirror' ),
			'elegant'                 => __( 'Elegant', 'editor-tweaks-for-codemirror' ),
			'idea'                    => __( 'IDEA', 'editor-tweaks-for-codemirror' ),
			'md-like'                 => __( 'MD Like', 'editor-tweaks-for-codemirror' ),
			'xq-light'                => __( 'XQ Light', 'editor-tweaks-for-codemirror' ),
		);
		?>
		<select name="editor_tweaks_for_codemirror_options[theme]" id="etcm_theme">
			<?php foreach ( $themes as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $theme, $value ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Render font section
	 */
	public function render_font_section() {
		echo '<p>' . esc_html__( 'Customize font settings for the editor.', 'editor-tweaks-for-codemirror' ) . '</p>';
	}

	/**
	 * Render font family field
	 */
	public function render_font_family_field() {
		$options     = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$font_family = isset( $options['font_family'] ) ? $options['font_family'] : '';
		?>
		<select name="editor_tweaks_for_codemirror_options[font_family]" id="etcm_font_family" class="editor-tweaks-for-codemirror-font-select" style="min-width: 300px;">
			<option value=""><?php esc_html_e( 'Default (inherit)', 'editor-tweaks-for-codemirror' ); ?></option>
			<?php if ( ! empty( $font_family ) ) : ?>
				<option value="<?php echo esc_attr( $font_family ); ?>" selected><?php echo esc_html( $font_family ); ?></option>
			<?php endif; ?>
		</select>
		<?php $this->render_help_icon( __( 'Select a font family. Fonts are loaded from Fontsource.', 'editor-tweaks-for-codemirror' ) ); ?>
		<?php
	}

	/**
	 * Render font weight field
	 */
	public function render_font_weight_field() {
		$options     = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$font_weight = isset( $options['font_weight'] ) ? $options['font_weight'] : '400';
		$weights     = array(
			'100' => __( 'Thin (100)', 'editor-tweaks-for-codemirror' ),
			'200' => __( 'Extra Light (200)', 'editor-tweaks-for-codemirror' ),
			'300' => __( 'Light (300)', 'editor-tweaks-for-codemirror' ),
			'400' => __( 'Normal (400)', 'editor-tweaks-for-codemirror' ),
			'500' => __( 'Medium (500)', 'editor-tweaks-for-codemirror' ),
			'600' => __( 'Semi Bold (600)', 'editor-tweaks-for-codemirror' ),
			'700' => __( 'Bold (700)', 'editor-tweaks-for-codemirror' ),
			'800' => __( 'Extra Bold (800)', 'editor-tweaks-for-codemirror' ),
			'900' => __( 'Black (900)', 'editor-tweaks-for-codemirror' ),
		);
		?>
		<select name="editor_tweaks_for_codemirror_options[font_weight]" id="etcm_font_weight">
			<?php foreach ( $weights as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $font_weight, $value ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php $this->render_help_icon( __( 'Select font weight', 'editor-tweaks-for-codemirror' ) ); ?>
		<?php
	}

	/**
	 * Render font size field
	 */
	public function render_font_size_field() {
		$options   = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$font_size = isset( $options['font_size'] ) ? $options['font_size'] : 14;
		?>
		<input type="number" name="editor_tweaks_for_codemirror_options[font_size]" id="etcm_font_size" 
				value="<?php echo esc_attr( $font_size ); ?>" min="10" step="1">
		<?php $this->render_help_icon( __( 'Font size in pixels (minimum: 10).', 'editor-tweaks-for-codemirror' ) ); ?>
		<?php
	}

	/**
	 * Render line height field
	 */
	public function render_line_height_field() {
		$options     = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$line_height = isset( $options['line_height'] ) ? $options['line_height'] : '1.5';
		?>
		<input type="text" name="editor_tweaks_for_codemirror_options[line_height]" id="etcm_line_height" 
				value="<?php echo esc_attr( $line_height ); ?>" placeholder="1.5 or 1.5em or 24px">
		<?php $this->render_help_icon( __( 'Line height as multiplier (1.5) or with unit (1.5em, 24px). Default: 1.5', 'editor-tweaks-for-codemirror' ) ); ?>
		<?php
	}

	/**
	 * Render letter spacing field
	 */
	public function render_letter_spacing_field() {
		$options        = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$letter_spacing = isset( $options['letter_spacing'] ) ? $options['letter_spacing'] : 0;
		?>
		<input type="number" name="editor_tweaks_for_codemirror_options[letter_spacing]" id="etcm_letter_spacing" 
				value="<?php echo esc_attr( $letter_spacing ); ?>" step="0.1" min="-5" max="10">
		<?php $this->render_help_icon( __( 'Letter spacing in pixels. Can be negative or positive. Default: 0', 'editor-tweaks-for-codemirror' ) ); ?>
		<?php
	}

	/**
	 * Render display section
	 */
	public function render_display_section() {
		echo '<p>' . esc_html__( 'Configure display options for the editor.', 'editor-tweaks-for-codemirror' ) . '</p>';
	}

	/**
	 * Render line numbers field
	 */
	public function render_line_numbers_field() {
		$options      = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$line_numbers = isset( $options['line_numbers'] ) ? $options['line_numbers'] : true;
		?>
		<input type="checkbox" name="editor_tweaks_for_codemirror_options[line_numbers]" id="etcm_line_numbers" 
				value="1" <?php checked( $line_numbers, true ); ?>>
		<label for="etcm_line_numbers"><?php esc_html_e( 'Enable line numbers', 'editor-tweaks-for-codemirror' ); ?></label>
		<?php
	}

	/**
	 * Render word wrap field
	 */
	public function render_word_wrap_field() {
		$options   = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$word_wrap = isset( $options['word_wrap'] ) ? $options['word_wrap'] : false;
		?>
		<input type="checkbox" name="editor_tweaks_for_codemirror_options[word_wrap]" id="etcm_word_wrap" 
				value="1" <?php checked( $word_wrap, true ); ?>>
		<label for="etcm_word_wrap"><?php esc_html_e( 'Enable word wrap', 'editor-tweaks-for-codemirror' ); ?></label>
		<?php
	}

	/**
	 * Render ruler column field
	 */
	public function render_ruler_column_field() {
		$options      = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$ruler_column = isset( $options['ruler_column'] ) ? $options['ruler_column'] : 0;
		?>
		<input type="number" name="editor_tweaks_for_codemirror_options[ruler_column]" id="etcm_ruler_column" 
				value="<?php echo esc_attr( $ruler_column ); ?>" min="0" max="200" step="1">
		<?php $this->render_help_icon( __( 'Show ruler at column (0 to disable). Useful for line length guidelines.', 'editor-tweaks-for-codemirror' ) ); ?>
		<?php
	}

	/**
	 * Render current line highlight field
	 */
	public function render_current_line_highlight_field() {
		$options                = get_option( 'editor_tweaks_for_codemirror_options', array() );
		$current_line_highlight = isset( $options['current_line_highlight'] ) ? $options['current_line_highlight'] : false;
		?>
		<input type="checkbox" name="editor_tweaks_for_codemirror_options[current_line_highlight]" id="etcm_current_line_highlight" 
				value="1" <?php checked( $current_line_highlight, true ); ?>>
		<label for="etcm_current_line_highlight"><?php esc_html_e( 'Enable current line highlighting.', 'editor-tweaks-for-codemirror' ); ?></label>
		<?php $this->render_help_icon( __( 'Highlight the line where the cursor is located with a subtle background color.', 'editor-tweaks-for-codemirror' ) ); ?>
		<?php
	}

	/**
	 * Render help icon with a CSS-only tooltip.
	 *
	 * @param string $text Help text to display in tooltip.
	 */
	private function render_help_icon( $text ) {
		printf(
			'<span class="editor-tweaks-for-codemirror-tip dashicons dashicons-editor-help" tabindex="0" role="note" aria-label="%1$s" data-tip="%1$s"></span>',
			esc_attr( $text )
		);
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'settings_page_editor-tweaks-for-codemirror' !== $hook ) {
			return;
		}

		/**
		 * Enqueue Select2
		 */
		wp_enqueue_style(
			'select2',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/select2/css/select2.min.css',
			array(),
			'4.1.0'
		);

		wp_enqueue_style(
			'editor-tweaks-for-codemirror-admin',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/css/admin.css',
			array( 'select2' ),
			EDITOR_TWEAKS_FOR_CODEMIRROR_VERSION
		);

		/**
		 * Enqueue editor CSS for theme styles in preview
		 */
		wp_enqueue_style(
			'editor-tweaks-for-codemirror-editor',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/css/editor.css',
			array(),
			EDITOR_TWEAKS_FOR_CODEMIRROR_VERSION
		);

		wp_enqueue_script(
			'select2',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/select2/js/select2.min.js',
			array( 'jquery' ),
			'4.1.0',
			true
		);

		// Select2 UI strings for the site locale.
		$select2_language = $this->get_select2_language( determine_locale() );

		wp_enqueue_script(
			'select2-i18n',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/select2/js/i18n/' . $select2_language . '.js',
			array( 'select2' ),
			'4.1.0',
			true
		);

		/**
		 * Enqueue WordPress code editor (CodeMirror)
		 */
		wp_enqueue_code_editor( array( 'type' => 'text/javascript' ) );

		$options = get_option( 'editor_tweaks_for_codemirror_options', array() );

		wp_enqueue_script(
			'editor-tweaks-for-codemirror-admin',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery', 'select2', 'select2-i18n', 'wp-codemirror' ),
			EDITOR_TWEAKS_FOR_CODEMIRROR_VERSION,
			true
		);

		/**
		 * Load sample files for preview
		 */
		$sample_files = array();
		$file_types   = array( 'php', 'js', 'css', 'html', 'json' );

		foreach ( $file_types as $type ) {
			$file_path = EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'sample-data/example.' . $type;
			if ( file_exists( $file_path ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- This is a mock file for testing.
				$sample_files[ $type ] = file_get_contents( $file_path );
			}
		}

		/**
		 * Pass current settings to JavaScript
		 */
		wp_localize_script(
			'editor-tweaks-for-codemirror-admin',
			'editorTweaksForCodeMirrorAdminSettings',
			array(
				'theme'                => isset( $options['theme'] ) ? $options['theme'] : 'default',
				'fontFamily'           => isset( $options['font_family'] ) ? $options['font_family'] : '',
				'fontWeight'           => isset( $options['font_weight'] ) ? $options['font_weight'] : '400',
				'fontSize'             => isset( $options['font_size'] ) ? $options['font_size'] : 14,
				'lineHeight'           => isset( $options['line_height'] ) ? $options['line_height'] : '1.5',
				'lineNumbers'          => isset( $options['line_numbers'] ) ? $options['line_numbers'] : true,
				'wordWrap'             => isset( $options['word_wrap'] ) ? $options['word_wrap'] : false,
				'rulerColumn'          => isset( $options['ruler_column'] ) ? $options['ruler_column'] : 0,
				'currentLineHighlight' => isset( $options['current_line_highlight'] ) ? $options['current_line_highlight'] : false,
				'pluginUrl'            => EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL,
				'sampleFiles'          => $sample_files,
				'select2Language'      => $select2_language,
				'i18n'                 => array(
					'selectTheme'  => __( 'Select a theme…', 'editor-tweaks-for-codemirror' ),
					'searchFont'   => __( 'Search and select a font…', 'editor-tweaks-for-codemirror' ),
					'noFontsFound' => __( 'No fonts found', 'editor-tweaks-for-codemirror' ),
				),
			)
		);
	}

	/**
	 * Map a WordPress locale (pt_BR) to a Select2 i18n file (pt-BR, then pt).
	 *
	 * @param string $locale WordPress locale.
	 * @return string Select2 language file name without .js.
	 */
	private function get_select2_language( $locale ) {
		$tag        = str_replace( '_', '-', $locale );
		$candidates = array_unique( array( $tag, explode( '-', $tag )[0] ) );

		foreach ( $candidates as $candidate ) {
			if ( preg_match( '/^[A-Za-z]+(-[A-Za-z]+)?$/', $candidate )
				&& file_exists( EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'assets/select2/js/i18n/' . $candidate . '.js' ) ) {
				return $candidate;
			}
		}

		return 'en';
	}
}

