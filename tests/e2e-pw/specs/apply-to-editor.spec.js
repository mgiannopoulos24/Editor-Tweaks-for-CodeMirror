import { test, expect } from '@playwright/test';
import { gotoSettings, chooseSelect2, resetOptions, routes } from './helpers';

test.describe( 'Saved settings reach the Theme File Editor', () => {
	test.beforeAll( () => resetOptions() );
	test.afterAll( () => resetOptions() );

	test( 'theme and font size are applied to theme-editor.php', async ( { page } ) => {
		const s = await gotoSettings( page );

		await chooseSelect2( page, 'etcm_theme', 'Dracula' );
		await s.fontSize.fill( '18' );
		await s.save.click();
		await page.waitForURL( /settings-updated=true/ );
		await expect( page.locator( '#setting-error-settings_updated' ) ).toBeVisible();

		// The form is re-rendered from the saved options.
		await expect( s.theme ).toHaveValue( 'dracula' );
		await expect( s.fontSize ).toHaveValue( '18' );

		await page.goto( routes.themeEditor );
		const editor = page.locator( '#template .CodeMirror' );
		await expect( editor ).toBeVisible();
		await expect( editor ).toHaveClass( /cm-s-dracula/ );
		await expect( editor ).toHaveCSS( 'font-size', '18px' );
	} );
} );
