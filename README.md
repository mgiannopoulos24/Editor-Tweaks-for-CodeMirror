# Editor Tweaks for CodeMirror

Tweak every CodeMirror editor in WordPress with custom themes, fonts, and display options.

## Description

Editor Tweaks for CodeMirror provides powerful customization options for WordPress CodeMirror editors, making it easy to personalize your code editing experience. Instead of using default editor settings, you can customize themes, font sizes, line numbers, and word wrapping across all CodeMirror instances in WordPress.

## Features

- **40+ Themes**: Choose from a wide selection of CodeMirror 5 themes including Monokai, Dracula, Material, Solarized, Nord, GitHub, and many more
- **Font Customization**: 
  - Select from the Google Fonts library (the font list is bundled; font files load from Google Fonts)
  - Adjust font weight (100-900)
  - Customize font size (minimum 10px, no maximum)
  - Set line height with multipliers (1.5) or custom units (1.5em, 24px, etc.)
  - Adjust letter spacing in pixels (supports negative and positive values)
- **Display Options**: 
  - Toggle line numbers on/off
  - Enhanced gutter spacing for better readability
  - Enable/disable word wrap
  - Set ruler column for line length guidelines
  - Highlight current line with subtle background color
- **Internationalization (i18n)**: Translation-ready; translations are delivered through translate.wordpress.org language packs and load just in time from `wp-content/languages/plugins`
- **Live Preview**: Real-time preview editor that updates instantly as you change settings
- **Sample Code Preview**: Test your settings with sample JavaScript, PHP, CSS, HTML, and JSON files
- **Easy Settings**: Intuitive admin interface with help icon tooltips for all options
- **Clean Uninstall**: Removes all plugin data when uninstalled
- **Universal Application**: Settings apply to all CodeMirror editors throughout WordPress
- **Bundled Themes**: CodeMirror theme stylesheets ship with the plugin and load only when selected
- **Lightweight**: Minimal overhead with vanilla JavaScript implementation

## Installation

### WordPress Installation

1. Download the plugin zip file or clone this repository
2. Upload the `editor-tweaks-for-codemirror` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to **Settings → Editor Tweaks for CodeMirror** to configure your editor preferences

### Development Setup

This project uses [Bun](https://bun.sh) as the package manager and [@wordpress/env](https://github.com/WordPress/gutenberg/tree/trunk/packages/env) for local WordPress development.

#### Prerequisites

- [Bun](https://bun.sh) installed on your system
- Docker and Docker Compose (required for wp-env)

#### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/mgiannopoulos24/editor-tweaks-for-codemirror.git
cd editor-tweaks-for-codemirror
```

2. **Install dependencies**
```bash
bun install
```

3. **Start the local WordPress environment**
```bash
bun run start
```
This will start a WordPress instance at `http://localhost:8888`

## Available Scripts

### Development Scripts

- `bun run start` - Start the local WordPress environment using wp-env
- `bun run stop` - Stop the WordPress environment
- `bun run destroy` - Destroy the WordPress environment (removes all data)
- `bun run shell` - Open a bash shell in the WordPress CLI container

### Build Scripts

- `bun run bundle` - Create a distribution zip file (`build/editor-tweaks-for-codemirror.zip`) containing the plugin files
- `bun run lint` - Run PHP linting on all PHP files in the project

### Test Scripts

- `bun run test:e2e:install` - Download the Chromium build Playwright needs (once)
- `bun run test:e2e` - Run the Playwright end-to-end tests against the wp-env site (started if not running)
- `bun run test:e2e:report` - Open the HTML report of the last run

### Translation Scripts

- `bun run i18n` - Regenerate the POT template (e.g. after adding translatable strings). Translations themselves are managed on translate.wordpress.org, so no `.po`/`.mo` files are bundled.

### Maintenance Scripts

- `bun run fonts:update` - Refresh the bundled Google Fonts catalog (`assets/data/google-fonts.json`)

## Usage

1. Navigate to **Settings → Editor Tweaks for CodeMirror** in your WordPress admin
2. Configure your preferences:
   - **Theme**: Select from 40+ available CodeMirror 5 themes (preview updates instantly)
   - **Font Family**: Search and select any Google Fonts family
   - **Font Weight**: Choose font weight (100-900)
   - **Font Size**: Set font size in pixels (minimum 10px)
   - **Line Height**: Set line height as multiplier (1.5) or with units (1.5em, 24px, etc.)
   - **Letter Spacing**: Adjust letter spacing in pixels (can be negative or positive, default: 0)
   - **Line Numbers**: Toggle line number display
   - **Word Wrap**: Enable or disable word wrapping
   - **Ruler Column**: Set a column guide for line length (0 to disable)
   - **Highlight Current Line**: Enable highlighting of the line where the cursor is located
3. Use the **Live Preview** section to see your changes in real-time
4. Test different file types (JavaScript, PHP, CSS, HTML, JSON) in the preview
5. Click **Save Settings** to apply your customizations
6. Your settings will automatically apply to all CodeMirror editors in WordPress, including:
   - Theme editor
   - Plugin editor
   - Customizer code editor
   - Any other CodeMirror 5 instance where the plugin is loaded

**Note:** The Gutenberg Code block and Custom HTML block are not CodeMirror editors, so these settings do not apply to them.

## Project Structure

```
editor-tweaks-for-codemirror/
├── assets/           # CSS and JavaScript files
│   ├── css/         # Stylesheets
│   │   ├── admin.css
│   │   └── editor.css
│   ├── js/          # JavaScript files
│   │   ├── admin.js
│   │   └── editor.js
│   ├── data/        # Bundled Google Fonts catalog (google-fonts.json)
│   └── vendor/      # Third-party libraries
│       ├── codemirror/theme/  # CodeMirror 5.65.16 theme stylesheets
│       └── select2/           # Select2 library files
├── includes/        # PHP class files
│   ├── class-editor-tweaks-for-codemirror.php
│   ├── class-editor-tweaks-for-codemirror-admin.php
│   ├── class-editor-tweaks-for-codemirror-editor.php
│   └── class-editor-tweaks-for-codemirror-autoloader.php
├── views/           # Admin view templates
│   └── admin-settings.php
├── sample-data/    # Sample code files for preview
│   ├── example.js
│   ├── example.php
│   ├── example.css
│   ├── example.html
│   └── example.json
├── languages/       # POT template (regenerate with `bun run i18n`)
├── scripts/         # Maintenance scripts (Google Fonts catalog)
├── tests/
│   └── e2e-pw/      # Playwright end-to-end tests
├── editor-tweaks-for-codemirror.php   # Main plugin file
├── package.json     # Dependencies and scripts
└── .wp-env.json     # WordPress environment configuration
```

## Requirements

- WordPress 5.0 or higher
- PHP 8.3 or higher (for development)
- Bun (for development)

## Development

The plugin uses WordPress's standard plugin structure and follows WordPress coding standards. The local development environment is configured to use:

- WordPress latest version
- PHP 8.3
- Twenty Twenty-Five theme
- Debug mode enabled

## Customization

### Available Themes

The plugin supports all CodeMirror 5 themes, including:
- **Dark Themes**: Monokai, Dracula, Material, Material Darker, Material Ocean, Solarized Dark, Nord, Ambiance, Blackboard, Cobalt, and many more
- **Light Themes**: Solarized Light, GitHub Light, Eclipse, Elegant, IDEA, and more

Theme stylesheets are bundled with the plugin and loaded only when selected, so no manual installation is required.

### External Services

The selected font family is loaded from Google Fonts (`fonts.googleapis.com`). No request is made when the font family is left on "Default (inherit)". See the External Services section of `readme.txt` for details.

### Extending Settings

Add new settings by:
1. Registering the field in `register_settings()` method in `class-editor-tweaks-for-codemirror-admin.php`
2. Adding a render method for the field
3. Applying the setting in `assets/js/editor.js` and `assets/js/admin.js` for preview

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes and version history.

## License

This plugin is licensed under the GPL v2 or later license.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
