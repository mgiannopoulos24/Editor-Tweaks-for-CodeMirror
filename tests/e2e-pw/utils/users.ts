export type TestUser = {
	user: string;
	password: string;
	email: string;
	role: string;
};

/** Dedicated admin account created by global-setup.ts. */
export const ADMIN_USER: TestUser = {
	user: process.env.WP_ADMIN_USER ?? 'e2e_admin',
	password: process.env.WP_ADMIN_PASSWORD ?? 'password',
	email: 'e2e_admin@test.local',
	role: 'administrator',
};

/** Second administrator used to verify per-user preferences. */
export const SECOND_ADMIN: TestUser = {
	user: 'etcm_second_admin',
	password: 'password',
	email: 'etcm-second-admin@example.com',
	role: 'administrator',
};
