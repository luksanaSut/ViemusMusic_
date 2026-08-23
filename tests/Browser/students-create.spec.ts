import { expect, test, type Page } from '@playwright/test';

async function openCreateStudent(page: Page): Promise<void> {
    await page.goto('/students/create');
    await expect(page.getByRole('heading', { name: 'เพิ่มข้อมูลนักเรียน' })).toBeVisible();
}

async function submit(page: Page): Promise<void> {
    await page.getByRole('button', { name: 'บันทึก' }).click();
}

test.beforeEach(async ({ page }) => {
    await openCreateStudent(page);
});

test('เพิ่มนักเรียนสำเร็จ', async ({ page }) => {
    const code = `PW${Date.now()}`;

    await page.locator('input[name="student_code"]').fill(code);
    await page.locator('input[name="full_name"]').fill('นักเรียน Playwright');
    await page.locator('input[name="nickname"]').fill('เพลย์');
    await submit(page);

    await expect(page).toHaveURL(/\/students\/\d+$/);
    await expect(page.getByText('เพิ่มข้อมูลนักเรียนเรียบร้อยแล้ว')).toBeVisible();
    await expect(page.getByText('นักเรียน Playwright', { exact: true })).toBeVisible();
});

test('ไม่ส่งฟอร์มเมื่อไม่กรอกรหัสนักเรียน', async ({ page }) => {
    const codeInput = page.locator('input[name="student_code"]');
    await page.locator('input[name="full_name"]').fill('นักเรียนไม่มีรหัส');
    await submit(page);

    await expect(page).toHaveURL(/\/students\/create$/);
    await expect(codeInput).toBeFocused();
    await expect(codeInput).toHaveJSProperty('validity.valueMissing', true);
});

test('ไม่ส่งฟอร์มเมื่อไม่กรอกชื่อ', async ({ page }) => {
    const nameInput = page.locator('input[name="full_name"]');
    await page.locator('input[name="student_code"]').fill(`NONAME${Date.now()}`);
    await submit(page);

    await expect(page).toHaveURL(/\/students\/create$/);
    await expect(nameInput).toBeFocused();
    await expect(nameInput).toHaveJSProperty('validity.valueMissing', true);
});

test('ไม่ส่งฟอร์มเมื่อ email ผิดรูปแบบ', async ({ page }) => {
    const emailInput = page.locator('input[name="email"]');
    await page.locator('input[name="student_code"]').fill(`EMAIL${Date.now()}`);
    await page.locator('input[name="full_name"]').fill('นักเรียนอีเมลผิด');
    await emailInput.fill('invalid-email');
    await submit(page);

    await expect(page).toHaveURL(/\/students\/create$/);
    await expect(emailInput).toBeFocused();
    await expect(emailInput).toHaveJSProperty('validity.typeMismatch', true);
});

test('จัดรูปแบบเบอร์โทรอัตโนมัติ', async ({ page }) => {
    const phoneInput = page.locator('input[name="phone"]');
    await phoneInput.fill('0812345678');

    await expect(phoneInput).toHaveValue('081-234-5678');
});

test('เปลี่ยนรหัสนักเรียนเป็นตัวพิมพ์ใหญ่อัตโนมัติ', async ({ page }) => {
    const codeInput = page.locator('input[name="student_code"]');
    await codeInput.fill('std-ab123');

    await expect(codeInput).toHaveValue('STD-AB123');
});
