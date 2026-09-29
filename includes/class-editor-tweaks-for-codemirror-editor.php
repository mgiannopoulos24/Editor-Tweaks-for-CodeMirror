<?php
/**
 * CodeMirror editor modifications
 *
 * @package Editor_Tweaks_For_CodeMirror
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Editor_Tweaks_For_CodeMirror_Editor class
 *
 * @package Editor_Tweaks_For_CodeMirror
 */
class Editor_Tweaks_For_CodeMirror_Editor {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_editor_assets' ) );
		add_filter( 'wp_code_editor_settings', array( $this, 'modify_code_editor_settings' ), 10, 2 );
	}

	/**
	 * Enqueue editor assets
	 */
	public function enqueue_editor_assets() {
		/**
		 * Only enqueue on pages that use CodeMirror
		 */
		if ( ! $this->should_enqueue_assets() ) {
			return;
		}

		$options = get_option( 'editor_tweaks_for_codemirror_options', array() );

		/**
		 * Enqueue CodeMirror 6 assets
		 */
		wp_enqueue_script(
			'editor-tweaks-for-codemirror-editor',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/js/editor.js',
			array(),
			EDITOR_TWEAKS_FOR_CODEMIRROR_VERSION,
			true
		);

		wp_enqueue_style(
			'editor-tweaks-for-codemirror-editor',
			EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/css/editor.css',
			array(),
			EDITOR_TWEAKS_FOR_CODEMIRROR_VERSION
		);

		/**
		 * Load font from Google Fonts if font family is set
		 */
		$font_family = isset( $options['font_family'] ) ? $options['font_family'] : '';
		$font_weight = isset( $options['font_weight'] ) ? $options['font_weight'] : '400';
		$font_url    = self::get_google_font_url( $font_family, $font_weight );
		if ( $font_url ) {
			// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts URLs must not carry a ver query arg.
			wp_enqueue_style( 'editor-tweaks-for-codemirror-font', $font_url, array(), null );
		}

		/**
		 * Pass settings to JavaScript
		 */
		wp_localize_script(
			'editor-tweaks-for-codemirror-editor',
			'editorTweaksForCodeMirrorSettings',
			array(
				'theme'                => isset( $options['theme'] ) ? $options['theme'] : 'default',
				'themeBaseUrl'         => EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_URL . 'assets/vendor/codemirror/theme/',
				'fontFamily'           => $font_family,
				'fontWeight'           => isset( $options['font_weight'] ) ? $options['font_weight'] : '400',
				'fontSize'             => isset( $options['font_size'] ) ? $options['font_size'] : 14,
				'lineHeight'           => isset( $options['line_height'] ) ? $options['line_height'] : '1.5',
				'letterSpacing'        => isset( $options['letter_spacing'] ) ? floatval( $options['letter_spacing'] ) : 0,
				'lineNumbers'          => isset( $options['line_numbers'] ) ? $options['line_numbers'] : true,
				'wordWrap'             => isset( $options['word_wrap'] ) ? $options['word_wrap'] : false,
				'rulerColumn'          => isset( $options['ruler_column'] ) ? $options['ruler_column'] : 0,
				'currentLineHighlight' => isset( $options['current_line_highlight'] ) ? $options['current_line_highlight'] : false,
			)
		);
	}

	/**
	 * Get the bundled Google Fonts catalog.
	 *
	 * @return array Map of font family => list of available weights.
	 */
	public static function get_google_fonts() {
		static $fonts = null;

		if ( null === $fonts ) {
			$fonts = wp_json_file_decode(
				EDITOR_TWEAKS_FOR_CODEMIRROR_PLUGIN_DIR . 'assets/data/google-fonts.json',
				array( 'associative' => true )
			);
			if ( ! is_array( $fonts ) ) {
				$fonts = array();
			}
		}

		return $fonts;
	}

	/**
	 * Build the Google Fonts stylesheet URL for a font family.
	 *
	 * Requests the regular (400) and selected weights, limited to the weights the family has.
	 *
	 * @param string $font_family Font family name.
	 * @param string $font_weight Selected font weight.
	 * @return string Stylesheet URL, or empty string if the family is not in the catalog.
	 */
	public static function get_google_font_url( $font_family, $font_weight ) {
		$fonts = self::get_google_fonts();

		if ( empty( $font_family ) || ! isset( $fonts[ $font_family ] ) ) {
			return '';
		}

		$available = $fonts[ $font_family ];
		$weights   = array_values( array_intersect( $available, array( '400', (string) $font_weight ) ) );
		if ( empty( $weights ) ) {
			$weights = array( $available[0] );
		}

		return 'https://fonts.googleapis.com/css2?family=' . str_replace( '%20', '+', rawurlencode( $font_family ) )
			. ':wght@' . implode( ';', $weights ) . '&display=swap';
	}

	/**
	 * Check if assets should be enqueued
	 *
	 * @return bool
	 */
	private function should_enqueue_assets() {
		/**
		 * Enqueue on admin pages that typically use code editors
		 */
		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( $screen && (
				'post' === $screen->base ||
				'theme-editor' === $screen->id ||
				'plugin-editor' === $screen->id ||
				'customize' === $screen->id
			) ) {
				return true;
			}
		}

		/**
		 * Add custom conditions as needed
		 */
		return apply_filters( 'editor_tweaks_for_codemirror_should_enqueue', false );
	}

	/**
	 * Modify code editor settings
	 *
	 * @param array $settings Editor settings.
	 * @return array Modified settings.
	 */
	public function modify_code_editor_settings( $settings ) {
		$options = get_option( 'editor_tweaks_for_codemirror_options', array() );

		if ( isset( $options['theme'] ) ) {
			$settings['theme'] = $options['theme'];
		}

		if ( isset( $options['font_size'] ) ) {
			$settings['fontSize'] = $options['font_size'];
		}

		if ( isset( $options['line_numbers'] ) ) {
			$settings['lineNumbers'] = $options['line_numbers'];
		}

		if ( isset( $options['word_wrap'] ) ) {
			$settings['wordWrap'] = $options['word_wrap'];
		}

		return $settings;
	}
}
