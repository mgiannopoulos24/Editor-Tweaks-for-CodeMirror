import { ADMIN_USER } from './utils/users';
import { ensureUser, waitForWordPress, wpCliArgs } from './utils/wp-cli';

/** Make sure wp-env is installed, the plugin is active and the test admin exists. */
export default function globalSetup(): void {
	waitForWordPress();
	wpCliArgs( [ 'plugin', 'activate', 'editor-tweaks-for-codemirror' ] );
	ensureUser( ADMIN_USER );
}
