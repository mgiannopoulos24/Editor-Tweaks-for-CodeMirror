# Agent Guide

WordPress plugin that customizes CodeMirror **5** instances (not CodeMirror 6) with themes, fonts, and display options.

## Verify

Run before reporting work as done:

- `bun run lint` — PHPCS with the project ruleset
- `bun run test:e2e` — Playwright; requires `bun run start` (wp-env)

## Key files

- `editor-tweaks-for-codemirror.php` — plugin bootstrap, constants, version
- `includes/class-editor-tweaks-for-codemirror-admin.php` — settings, sanitization, settings UI
- `includes/class-editor-tweaks-for-codemirror-editor.php` — asset enqueuing, localized settings
- `assets/js/editor.js` — applies settings to editor instances
- `assets/js/admin.js` — live admin preview
- `assets/css/editor.css` — editor styling

## Adding an editor setting

1. **Admin** (`class-editor-tweaks-for-codemirror-admin.php`): register in `register_settings()`, sanitize in `sanitize_settings()`, add a render method.
2. **Pass to JS** (`class-editor-tweaks-for-codemirror-editor.php`): add it to the `wp_localize_script` array.
3. **Apply** (`assets/js/editor.js`): add logic in `customizeEditor()`, targeting `.CodeMirror-code`, `.CodeMirror pre`, and `.CodeMirror-line`.
4. **Preview** (`assets/js/admin.js`): add to `settingsInputs` and wire up live updates.

## Gutters

Never apply `font-family`, `font-weight`, `font-size`, `letter-spacing`, or `line-height` to `.CodeMirror-gutters`, `.CodeMirror-gutter`, or `.CodeMirror-linenumber`. Explicitly reset gutter styles if inherited values leak in.

## PHP and i18n

- Use `__()` / `_e()` for string literals. Use `esc_html__()` / `esc_html_e()` only when the string contains a variable; escape at the output boundary instead.
- Text domain: `editor-tweaks-for-codemirror`.
- Do not add `.po`/`.mo` files or call `load_plugin_textdomain()`; WordPress.org loads translations.
- After adding strings, run `bun run i18n` (needs `bun run start`). Never edit the `.pot` by hand.

## JavaScript

- Vanilla JS, no build step.
- Each script has exactly one localized object, defined by its enqueue. Use it directly: no `typeof` guard, no inline fallback strings, no cross-referencing another file's object.

## CSS

- Prefix classes with `editor-tweaks-for-codemirror-`.

## Remote assets

Never load scripts, styles, or data from a CDN; bundle them. The only allowed remote request is Google Fonts for the selected font (documented under External Services in `readme.txt`).

## Docs and release

After a code change:

1. Update `README.md` (Features, Usage).
2. Mirror it in `readme.txt` (Features, Changelog).
3. Add a `CHANGELOG.md` entry (Keep a Changelog, SemVer).
4. Bump the version in `editor-tweaks-for-codemirror.php` (header and constant) and `package.json`.

Version bumps are for changes that ship to users: PHP/JS/CSS, behavior, settings, bundled assets. Repo-only changes — README/`readme.txt` wording, CI or GitHub workflow tweaks, dev dependency updates, tooling — do not need a version bump. Use judgment: if the change alters what users install or how a release is produced, bump it; otherwise skip the bump and the changelog entry.

## Scripts

```bash
bun run start        # Start wp-env
bun run stop         # Stop wp-env
bun run lint         # PHPCS
bun run test:e2e     # Playwright E2E
bun run i18n         # Regenerate POT
bun run bundle       # Distribution zip
bun run fonts:update # Refresh bundled Google Fonts catalog
```
