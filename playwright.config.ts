import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: './tests/Browser',
    fullyParallel: false,
    workers: 1,
    globalSetup: './playwright.global-setup.ts',
    reporter: [['list'], ['html', { open: 'never' }]],
    use: {
        baseURL: 'http://127.0.0.1:8010',
        channel: 'chromium',
        trace: 'on-first-retry',
        screenshot: 'only-on-failure',
    },
    webServer: {
        command: 'sh scripts/playwright-server.sh',
        url: 'http://127.0.0.1:8010/login',
        reuseExistingServer: false,
        timeout: 120_000,
    },
    projects: [
        {
            name: 'setup',
            testMatch: /auth\.setup\.ts/,
        },
        {
            name: 'chromium',
            dependencies: ['setup'],
            testIgnore: /auth\.setup\.ts/,
            use: {
                ...devices['Desktop Chrome'],
                storageState: 'playwright/.auth/admin.json',
            },
        },
    ],
});
