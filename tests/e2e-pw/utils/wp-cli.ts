import { execFileSync, execSync } from 'node:child_process';
import path from 'node:path';
import type { TestUser } from './users';

const PLUGIN_ROOT = path.resolve( __dirname, '../../..' );

/** Run WP-CLI with argv (no shell). */
export function wpCliArgs( args: string[] ): string {
	const quoted = args.map( ( arg ) => JSON.stringify( arg ) ).join( ' ' );

	return execSync( `bunx wp-env run cli wp ${ quoted }`, {
		encoding: 'utf8',
		stdio: 'pipe',
		cwd: PLUGIN_ROOT,
	} ).trim();
}

export function userExists( login: string ): boolean {
	try {
		wpCliArgs( [ 'user', 'get', login, '--field=ID' ] );
		return true;
	} catch {
		return false;
	}
}

export function ensureUser( account: TestUser ): void {
	if ( userExists( account.user ) ) {
		wpCliArgs( [
			'user',
			'update',
			account.user,
			`--user_pass=${ account.password }`,
			`--role=${ account.role }`,
			`--display_name=${ account.user }`,
		] );
		return;
	}

	wpCliArgs( [
		'user',
		'create',
		account.user,
		account.email,
		`--role=${ account.role }`,
		`--user_pass=${ account.password }`,
		`--display_name=${ account.user }`,
	] );
}

export function deleteUser( login: string ): void {
	try {
		wpCliArgs( [ 'user', 'delete', login, '--yes' ] );
	} catch {
		// Already deleted.
	}
}

/** Wait until wp-env has finished installing WordPress. */
export function waitForWordPress( timeoutMs = 120_000 ): void {
	const deadline = Date.now() + timeoutMs;

	while ( Date.now() < deadline ) {
		try {
			wpCliArgs( [ 'core', 'is-installed' ] );
			return;
		} catch {
			execFileSync( 'sleep', [ '2' ] );
		}
	}

	throw new Error( 'WordPress did not finish installing within 120 seconds.' );
}
