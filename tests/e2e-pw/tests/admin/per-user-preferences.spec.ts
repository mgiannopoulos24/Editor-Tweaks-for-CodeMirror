import { test, expect } from '../../utils/fixtures';
import { ensureValidAuth } from '../../utils/auth';
import { gotoSettings, chooseSelect2, resetOptions, routes } from '../../utils/helpers';
import { SECOND_ADMIN } from '../../utils/users';
import { deleteUser, ensureUser } from '../../utils/wp-cli';

test.describe( 'Per-user preferences', () => {
	test.describe.configure( { mode: 'serial' } );

	test.beforeAll( () => {
		resetOptions();
		ensureUser( SECOND_ADMIN );
	} );

	test.afterAll( () => {
		resetOptions();
		deleteUser( SECOND_ADMIN.user );
	} );

	test( 'site defaults are inherited until a user saves preferences', async ( { adminContext } ) => {
		const page = await adminContext.newPage();
		const s = await gotoSettings( page );

		await expect( page.locator( '.nav-tab-active' ) ).toContainText( 'My Preferences' );
		await expect( s.theme ).toHaveValue( 'default' );

		await page.getByRole( 'link', { name: 'Site Defaults' } ).click();
		await expect( page.locator( '.nav-tab-active' ) ).toContainText( 'Site Defaults' );

		await chooseSelect2( page, 'etcm_theme', 'Dracula' );
		await s.fontSize.fill( '18' );
		await s.save.click();
		await page.waitForURL( /settings-updated=true/ );
		await expect( page.locator( '#setting-error-settings_updated' ) ).toBeVisible();

		// The admin has not saved personal preferences, so they inherit the site defaults.
		await page.goto( routes.settings );
		await expect( page.locator( '.nav-tab-active' ) ).toContainText( 'My Preferences' );
		await expect( s.theme ).toHaveValue( 'dracula' );
		await expect( s.fontSize ).toHaveValue( '18' );

		await page.goto( routes.themeEditor );
		const editor = page.locator( '#template .CodeMirror' );
		await expect( editor ).toBeVisible();
		await expect( editor ).toHaveClass( /cm-s-dracula/ );
		await expect( editor ).toHaveCSS( 'font-size', '18px' );
	} );

	test( 'personal preferences override site defaults without changing them', async ( { adminContext } ) => {
		const page = await adminContext.newPage();
		const s = await gotoSettings( page );

		await chooseSelect2( page, 'etcm_theme', 'Nord' );
		await s.fontSize.fill( '16' );
		await s.save.click();
		await page.waitForURL( /settings-updated=true/ );
		await expect( page.locator( '#setting-error-settings_updated' ) ).toBeVisible();

		await expect( s.theme ).toHaveValue( 'nord' );
		await expect( s.fontSize ).toHaveValue( '16' );

		await page.goto( routes.themeEditor );
		const editor = page.locator( '#template .CodeMirror' );
		await expect( editor ).toHaveClass( /cm-s-nord/ );
		await expect( editor ).toHaveCSS( 'font-size', '16px' );

		// Site defaults are unchanged.
		await page.goto( `${ routes.settings }&scope=site` );
		await expect( s.theme ).toHaveValue( 'dracula' );
		await expect( s.fontSize ).toHaveValue( '18' );
	} );

	test( 'a second administrator keeps separate preferences', async ( { adminContext, browser } ) => {
		const page = await adminContext.newPage();
		await page.goto( routes.themeEditor );
		await expect( page.locator( '#template .CodeMirror' ) ).toHaveClass( /cm-s-nord/ );

		const origin = new URL( page.url() ).origin;
		const context = await browser.newContext( { baseURL: origin } );
		const secondPage = await context.newPage();

		try {
			await ensureValidAuth( secondPage, SECOND_ADMIN.user, SECOND_ADMIN.password );
			const s = await gotoSettings( secondPage );

			// The new administrator inherits the site defaults, not the first admin's preferences.
			await expect( s.theme ).toHaveValue( 'dracula' );
			await expect( s.fontSize ).toHaveValue( '18' );

			await chooseSelect2( secondPage, 'etcm_theme', 'Solarized Light' );
			await s.fontSize.fill( '20' );
			await s.save.click();
			await secondPage.waitForURL( /settings-updated=true/ );

			await secondPage.goto( routes.themeEditor );
			const secondEditor = secondPage.locator( '#template .CodeMirror' );
			await expect( secondEditor ).toHaveClass( /cm-s-solarized/ );
			await expect( secondEditor ).toHaveCSS( 'font-size', '20px' );
		} finally {
			await context.close();
		}

		// The first administrator is unaffected.
		await page.goto( routes.themeEditor );
		const editor = page.locator( '#template .CodeMirror' );
		await expect( editor ).toHaveClass( /cm-s-nord/ );
		await expect( editor ).toHaveCSS( 'font-size', '16px' );
	} );

	test( 'a user can reset preferences to inherit site defaults again', async ( { adminContext } ) => {
		const page = await adminContext.newPage();
		const s = await gotoSettings( page );

		// Currently has personal preferences (Nord, 16px).
		await expect( s.theme ).toHaveValue( 'nord' );
		await expect( s.fontSize ).toHaveValue( '16' );

		// Reset button is visible when personal preferences exist.
		const resetBtn = page.getByRole( 'button', { name: 'Reset to Site Defaults' } );
		await expect( resetBtn ).toBeVisible();
		await resetBtn.click();

		await page.waitForURL( /settings-reset=true/ );
		await expect( page.locator( '#setting-error-settings_reset' ) ).toBeVisible();

		// Should now inherit site defaults (Dracula, 18px).
		await expect( s.theme ).toHaveValue( 'dracula' );
		await expect( s.fontSize ).toHaveValue( '18' );

		// Reset button is hidden once preferences are reset to site defaults.
		await expect( page.getByRole( 'button', { name: 'Reset to Site Defaults' } ) ).toBeHidden();
	} );
} );
