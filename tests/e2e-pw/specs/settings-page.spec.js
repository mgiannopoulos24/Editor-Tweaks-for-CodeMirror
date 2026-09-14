import { test, expect } from '@playwright/test';
import { gotoSettings, resetOptions } from './helpers';

test.describe( 'Settings page', () => {
	test.beforeAll( () => resetOptions() );

	test( 'renders the form and a live preview with defaults', async ( { page } ) => {
		const s = await gotoSettings( page );

		// Three sections (theme, font, display), each a settings table. Headings
		// are matched by count so the test passes in any site language.
		await expect( s.form.locator( 'h2' ) ).toHaveCount( 3 );
		await expect( s.form.locator( 'table.form-table' ) ).toHaveCount( 3 );

		await expect( s.theme ).toHaveValue( 'default' );
		await expect( s.fontWeight ).toHaveValue( '400' );
		await expect( s.fontSize ).toHaveValue( '14' );
		await expect( s.lineNumbers ).toBeChecked();

		// Both pickers are upgraded to Select2.
		await expect( page.locator( '#etcm_theme + .select2' ) ).toBeVisible();
		await expect( page.locator( '#etcm_font_family + .select2' ) ).toBeVisible();

		// Preview shows the JavaScript sample with line numbers.
		await expect( s.preview ).toHaveClass( /cm-s-default/ );
		await expect( s.preview.locator( '.CodeMirror-linenumber' ).first() ).toBeVisible();
		await expect( s.preview.locator( '.CodeMirror-code' ) ).toContainText( 'function' );
	} );

	test( 'help icons carry their tooltip text', async ( { page } ) => {
		await gotoSettings( page );
		const tips = page.locator( '.editor-tweaks-for-codemirror-tip' );
		await expect( tips ).toHaveCount( 7 );
		await expect( tips.first() ).toHaveAttribute( 'role', 'note' );
		await expect( tips.first() ).toHaveAttribute( 'tabindex', '0' );
		for ( const tip of await tips.all() ) {
			const text = await tip.getAttribute( 'data-tip' );
			expect( text ).toBeTruthy();
			await expect( tip ).toHaveAttribute( 'aria-label', text );
		}
	} );
} );
