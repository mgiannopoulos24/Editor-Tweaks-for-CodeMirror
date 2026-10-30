import fs from 'node:fs';
import path from 'node:path';
import type { Page } from '@playwright/test';

const AUTH_DIR = path.join( __dirname, '../.auth' );

if ( ! fs.existsSync( AUTH_DIR ) ) {
	fs.mkdirSync( AUTH_DIR, { recursive: true } );
}

export function getAuthPath( username: string ): string {
	return path.join( AUTH_DIR, `${ username }.json` );
}

/**
 * Reuse the stored session when it belongs to `username`; otherwise log in and
 * save a fresh one. Keeps every spec independent of the one before it.
 */
export async function ensureValidAuth( page: Page, username: string, password: string ): Promise<void> {
	await page.goto( '/wp-admin/', { waitUntil: 'domcontentloaded' } );

	if ( await page.locator( '#wpadminbar' ).count() ) {
		const currentUser = await page
			.locator( '#wp-admin-bar-my-account .display-name' )
			.first()
			.innerText()
			.catch( () => '' );

		if ( currentUser.trim() === username ) {
			await page.context().storageState( { path: getAuthPath( username ) } );
			return;
		}

		await performLogout( page );
	}

	await performLogin( page, username, password );
	await page.context().storageState( { path: getAuthPath( username ) } );
}

async function performLogout( page: Page ): Promise<void> {
	await page.goto( '/wp-login.php?action=logout', { waitUntil: 'domcontentloaded' } );
	await page.getByRole( 'link', { name: 'log out' } ).click();
	await page.waitForURL( /\/wp-login\.php\?loggedout=true/ );
}

async function performLogin( page: Page, username: string, password: string ): Promise<void> {
	await page.goto( '/wp-login.php', { waitUntil: 'domcontentloaded' } );
	await page.locator( '#user_login' ).fill( username );
	await page.locator( '#user_pass' ).fill( password );
	await page.getByRole( 'button', { name: 'Log In' } ).click();
	await page.waitForURL( /\/wp-admin\// );
}
