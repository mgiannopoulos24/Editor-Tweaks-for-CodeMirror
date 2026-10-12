=== Editor Tweaks for CodeMirror ===
Contributors: mgiannopoulos24
Tags: codemirror, editor, theme, customize, font
Requires at least: 6.5
Tested up to: 7.1.3
Requires PHP: 8.1
Stable tag: 1.1.2
License: GPL v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Tweak every CodeMirror editor in WordPress with custom themes, fonts, and display options.

== Description ==

Editor Tweaks for CodeMirror provides powerful customization options for WordPress CodeMirror editors, making it easy to personalize your code editing experience. Instead of using default editor settings, you can customize themes, font sizes, line numbers, and word wrapping across all CodeMirror instances in WordPress.

= Features =

* 40+ Themes - Choose from a wide selection of CodeMirror 5 themes including Monokai, Dracula, Material, Solarized, Nord, GitHub, and many more
* Font Customization - Select from the Google Fonts library, adjust font weight, size, line height, and letter spacing
* Display Options - Toggle line numbers, word wrap, ruler column, and current line highlighting
* Live Preview - Real-time preview editor that updates instantly as you change settings
* Internationalization - Translation-ready; translations are delivered through translate.wordpress.org

== Installation ==

1. Upload the `editor-tweaks-for-codemirror` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Settings > Editor Tweaks for CodeMirror to configure your preferences

== Changelog ==

= 1.1.2 =
* Saved theme is now validated against the list of available themes, falling back to the default for any unrecognized value

= 1.1.1 =
* Fixed mismatched "Tested up to" value between the plugin header and readme.txt

= 1.1.0 =
* CodeMirror themes are now bundled with the plugin instead of loaded from a CDN
* Fonts are now loaded from Google Fonts; the font list ships with the plugin
* Fixed the Solarized Dark and MDN Like themes not loading
* Removed bundled translation files; translations come from translate.wordpress.org

= 1.0.0 =
* Initial release of Editor Tweaks for CodeMirror
* 40+ CodeMirror 5 themes support
* Font customization (family, weight, size, line height)
* Display options (line numbers, word wrap, ruler column)
* Live preview editor with real-time updates

== Frequently Asked Questions ==

= Does this plugin work with the Gutenberg code block? =
Yes, it applies to all CodeMirror editor instances in WordPress, including the code block in Gutenberg.

= Where are the settings? =
Go to Settings > Editor Tweaks for CodeMirror in your WordPress admin.

= How do I get support? =
For support, please visit the GitHub repository or the WordPress support forums.

== External Services ==

This plugin connects to Google Fonts to load the font family you choose for the editor. CodeMirror themes and the list of available fonts are bundled with the plugin and are not loaded remotely.

**Google Fonts (fonts.googleapis.com, fonts.gstatic.com)**

* **What it is used for**: Loading the stylesheet and font files of the font family selected in Settings > Editor Tweaks for CodeMirror.
* **When data is sent**: Only when a font family is selected. The request is made on admin pages with a code editor (theme and plugin file editors, the Customizer, the post editor) and on the plugin settings page when previewing a font. No request is made when the font family is set to "Default (inherit)".
* **What data is sent**: The font family name and weights. As with any web request, the visitor's browser also sends its IP address and user agent to Google. No account is required.
* **Provider**: Google LLC
* **Terms of Service**: https://policies.google.com/terms
* **Privacy Policy**: https://policies.google.com/privacy

== Screenshots ==

1. Admin settings page with live preview
2. Theme selection preview
3. Font customization options