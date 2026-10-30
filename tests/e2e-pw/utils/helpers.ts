import { expect } from '@playwright/test';
import type { Page } from '@playwright/test';
import { ADMIN_USER } from './users';
import { wpCliArgs } from './wp-cli';

export { ADMIN_USER };

export const routes = {
	settings: '/wp-admin/options-general.php?page=editor-tweaks-for-codemirror',
	themeEditor: '/wp-admin/theme-editor.php',
} as const;

export const OPTION_NAME = 'editor_tweaks_for_codemirror_options';

/** Delete the saved site and user options so every spec starts from the defaults. */
export function resetOptions(): void {
	try {
		wpCliArgs( [ 'option', 'delete', OPTION_NAME ] );
	} catch {
		// The option does not exist yet.
	}

	try {
		wpCliArgs( [ 'user', 'meta', 'delete', ADMIN_USER.user, OPTION_NAME ] );
	} catch {
		// The user has not saved preferences yet.
	}
}

/** Open the settings page and wait for the preview editor to initialise. */
export async function gotoSettings( page: Page ) {
	await page.goto( routes.settings );
	await expect( page.locator( '.editor-tweaks-for-codemirror-preview .CodeMirror' ) ).toBeVisible();
	return settings( page );
}

/** Locators for the settings form and preview. */
export function settings( page: Page ) {
	return {
		form: page.locator( '.editor-tweaks-for-codemirror-form' ),
		theme: page.locator( '#etcm_theme' ),
		fontFamily: page.locator( '#etcm_font_family' ),
		fontWeight: page.locator( '#etcm_font_weight' ),
		fontSize: page.locator( '#etcm_font_size' ),
		lineNumbers: page.locator( '#etcm_line_numbers' ),
		save: page.locator( '#submit' ),
		preview: page.locator( '.editor-tweaks-for-codemirror-preview .CodeMirror' ),
		previewFileType: page.locator( '#editor-tweaks-for-codemirror-preview-file-type' ),
	};
}

/** Pick an option in a Select2-driven select by typing into its search box. */
export async function chooseSelect2( page: Page, selectId: string, label: string ) {
	await page.locator( `#${ selectId } + .select2` ).click();
	const search = page.locator( '.select2-container--open .select2-search__field' );
	await search.fill( label );
	await page.locator( '.select2-results__option--highlighted', { hasText: label } ).click();
}
