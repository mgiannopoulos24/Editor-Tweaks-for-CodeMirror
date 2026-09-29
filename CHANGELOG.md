# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.1] - 2026-09-29

### Fixed
- "Tested up to" in the plugin header (7.1.2) did not match readme.txt (7.1); both are now 7.1

## [1.1.0] - 2026-09-29

### Changed
- CodeMirror 5.65.16 theme stylesheets are bundled in `assets/vendor/codemirror/theme/` instead of loaded from jsDelivr
- Fonts are loaded from Google Fonts instead of Fontsource/jsDelivr
- The font list is read from a bundled catalog (`assets/data/google-fonts.json`, refreshed with `bun run fonts:update`) instead of the Fontsource API
- Saved font families are validated against the bundled catalog
- `bun run i18n` now only regenerates the POT file
- Select2 moved to `assets/vendor/select2/`

### Fixed
- Solarized Dark theme requested a non-existent `solarized-dark.css`
- MDN Like theme mapped to a non-existent `md-like` theme

### Removed
- Bundled `.po`/`.mo` translation files (translations are delivered by translate.wordpress.org)
- `load_plugin_textdomain()` call; WordPress loads translations just in time
- `Plugin URI` header

## [1.0.0] - 2026-09-14

### Added
- Initial release of Editor Tweaks for CodeMirror
- 40+ CodeMirror 5 themes support
- Font customization:
  - Font family selection via Fontsource API
  - Font weight adjustment (100-900)
  - Font size customization (minimum 10px)
  - Line height configuration (multipliers or custom units)
- Display options:
  - Line numbers toggle
  - Word wrap toggle
  - Ruler column for line length guidelines
- Live preview editor with real-time updates
- Sample code preview for JavaScript, PHP, CSS, HTML, and JSON
- Intuitive admin interface
- Universal application to all CodeMirror editors in WordPress
- Dynamic theme loading from CDN

[1.1.1]: https://github.com/mgiannopoulos24/editor-tweaks-for-codemirror/releases/tag/v1.1.1
[1.1.0]: https://github.com/mgiannopoulos24/editor-tweaks-for-codemirror/releases/tag/v1.1.0
[1.0.0]: https://github.com/mgiannopoulos24/editor-tweaks-for-codemirror/releases/tag/v1.0.0
