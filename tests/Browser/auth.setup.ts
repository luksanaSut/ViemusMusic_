import { expect, test as setup } from '@playwright/test';

setup('ล็อกอินด้วยบัญชีผู้ดูแลระบบ', async ({ page }) => {
    await page.goto('/login');
    await page.locator('input[name="email"]').fill('playwright@viemus.test');
    await page.locator('input[name="password"]').fill('Playwright123!');
    await page.getByRole('button', { name: 'เข้าสู่ระบบ' }).click();
    await expect(page).not.toHaveURL(/\/login$/);
    await page.context().storageState({ path: 'playwright/.auth/admin.json' });
});
