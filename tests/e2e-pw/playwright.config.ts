import path from 'node:path';
import { defineConfig, devices } from '@playwright/test';

/**
 * Editor Tweaks for CodeMirror end-to-end tests.
 *
 *   bun run test:e2e
 *
 * Expects the wp-env site (bun run start) — it is started if not reachable.
 * The admin session is created once by the auth fixture and reused by the specs.
 */
const baseURL = process.env.WP_BASE_URL ?? 'http://localhost:8888';

export default defineConfig( {
	testDir: './tests',
	outputDir: path.join( __dirname, 'test-results' ),
	globalSetup: require.resolve( './global-setup.ts' ),
	// Specs share one WordPress install and one options row.
	fullyParallel: false,
	workers: 1,
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 1 : 0,
	reporter: [
		[ 'list' ],
		[ 'html', { open: 'never', outputFolder: path.join( __dirname, 'playwright-report' ) } ],
	],
	use: {
		baseURL,
		trace: 'retain-on-failure',
		screenshot: 'only-on-failure',
		actionTimeout: 30_000,
		navigationTimeout: 30_000,
	},
	timeout: 60_000,
	projects: [
		{
			name: 'chromium',
			use: {
				...devices[ 'Desktop Chrome' ],
			},
		},
	],
	webServer: {
		command: 'bun run start',
		url: baseURL,
		reuseExistingServer: true,
		timeout: 300_000,
	},
} );
