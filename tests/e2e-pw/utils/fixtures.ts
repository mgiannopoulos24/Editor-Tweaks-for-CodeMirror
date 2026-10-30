import fs from 'node:fs';
import { test as base } from '@playwright/test';
import type { BrowserContext } from '@playwright/test';
import { ensureValidAuth, getAuthPath } from './auth';
import { ADMIN_USER } from './users';

function storageStateFor( username: string ) {
	const authPath = getAuthPath( username );
	return fs.existsSync( authPath ) ? { storageState: authPath } : {};
}

export const test = base.extend< {
	adminContext: BrowserContext;
} >( {
	adminContext: async ( { browser }, use ) => {
		const context = await browser.newContext( storageStateFor( ADMIN_USER.user ) );
		const page = await context.newPage();
		await ensureValidAuth( page, ADMIN_USER.user, ADMIN_USER.password );
		await use( context );
		await page.close();
		await context.close();
	},
} );

export { expect } from '@playwright/test';
