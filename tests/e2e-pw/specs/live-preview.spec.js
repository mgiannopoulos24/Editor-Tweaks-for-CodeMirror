import { test, expect } from '@playwright/test';
import { gotoSettings, chooseSelect2, resetOptions } from './helpers';

test.describe( 'Live preview', () => {
	test.beforeAll( () => resetOptions() );

	test( 'theme, font size and line numbers update the preview without saving', async ( { page } ) => {
		const s = await gotoSettings( page );

		await chooseSelect2( page, 'etcm_theme', 'Dracula' );
		await expect( s.theme ).toHaveValue( 'dracula' );
		await expect( s.preview ).toHaveClass( /cm-s-dracula/ );

		await s.fontSize.fill( '18' );
		await expect( s.preview ).toHaveCSS( 'font-size', '18px' );

		await s.lineNumbers.uncheck();
		await expect( s.preview.locator( '.CodeMirror-linenumber' ) ).toHaveCount( 0 );
	} );

	test( 'file type switches the sample and its mode', async ( { page } ) => {
		const s = await gotoSettings( page );

		await s.previewFileType.selectOption( 'css' );
		await expect( s.preview.locator( '.CodeMirror-code' ) ).toContainText( '{' );
		await expect( s.preview.locator( '.cm-tag, .cm-qualifier, .cm-property' ).first() ).toBeVisible();

		await s.previewFileType.selectOption( 'json' );
		await expect( s.preview.locator( '.cm-string' ).first() ).toBeVisible();
	} );
} );
