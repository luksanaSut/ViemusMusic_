import { Buffer } from 'node:buffer';
import { expect, test, type Page } from '@playwright/test';

const field = (page: Page, name: string) => page.locator(`[name="${name}"]`);

async function openCreateTeacher(page: Page): Promise<void> {
    await page.goto('/teachers/create');
    await expect(page.getByRole('heading', { name: 'เพิ่มข้อมูลอาจารย์' })).toBeVisible();
}

async function fillRequiredFields(page: Page, code = `PWT${Date.now()}`): Promise<void> {
    await field(page, 'teacher_code').fill(code);
    await field(page, 'full_name').fill('อาจารย์ Playwright');
    await field(page, 'rate_amount').fill('650');
}

async function submit(page: Page): Promise<void> {
    await page.getByRole('button', { name: 'บันทึก' }).click();
}

test.beforeEach(async ({ page }) => {
    await openCreateTeacher(page);
});

test('เพิ่มอาจารย์พร้อมข้อมูลทุกส่วนสำเร็จ', async ({ page }) => {
    const code = `FULL${Date.now()}`;
    await fillRequiredFields(page, code);
    await field(page, 'nickname').fill('ครูเพลย์');
    await field(page, 'email').fill('teacher.playwright@gmail.com');
    await field(page, 'phone').fill('0812345678');
    await field(page, 'line_id').fill('teacher.playwright');
    await field(page, 'address').fill('กรุงเทพมหานคร');
    await field(page, 'employment_type').selectOption('full_time');
    await field(page, 'branch').fill('Cloud 11');
    await field(page, 'start_date').fill('2026-01-15');
    await page.getByLabel('สอนประจำ', { exact: true }).check();
    await page.getByLabel('เริ่มต้น (Beginner)', { exact: true }).check();

    await page.locator('#instrumentSearch').fill('เปียโน');
    await page.getByRole('button', { name: 'เปียโน', exact: true }).click();
    await expect(field(page, 'primary_instrument_id')).not.toHaveValue('');

    await field(page, 'rate_type').selectOption('per_hour');
    await field(page, 'transport_fee_type').selectOption('fixed_per_day');
    await field(page, 'transport_fee_amount').fill('120');
    await field(page, 'rate_note').fill('เรทสำหรับการทดสอบ');
    await field(page, 'bio').fill('ประวัติอาจารย์สำหรับการทดสอบ');
    await field(page, 'notes').fill('หมายเหตุภายในสำหรับการทดสอบ');
    await submit(page);

    await expect(page).toHaveURL(/\/teachers\/\d+$/);
    await expect(page.getByText('เพิ่มข้อมูลอาจารย์เรียบร้อยแล้ว')).toBeVisible();
    await expect(page.getByText('อาจารย์ Playwright', { exact: true })).toBeVisible();
    await expect(page.getByText(`(${code})`, { exact: true })).toBeVisible();
    await expect(page.getByText('Full-time', { exact: true })).toBeVisible();
    await expect(page.getByText('เปียโน ★', { exact: true })).toBeVisible();
});

test('ตรวจช่องบังคับ: รหัสอาจารย์ ชื่อ และจำนวนเงิน', async ({ page }) => {
    const codeInput = field(page, 'teacher_code');
    const nameInput = field(page, 'full_name');
    const rateInput = field(page, 'rate_amount');

    await nameInput.fill('อาจารย์ Playwright');
    await rateInput.fill('500');
    await submit(page);
    await expect(codeInput).toBeFocused();
    await expect(codeInput).toHaveJSProperty('validity.valueMissing', true);

    await codeInput.fill('REQ001');
    await nameInput.clear();
    await submit(page);
    await expect(nameInput).toBeFocused();
    await expect(nameInput).toHaveJSProperty('validity.valueMissing', true);

    await nameInput.fill('อาจารย์ Playwright');
    await rateInput.clear();
    await submit(page);
    await expect(rateInput).toBeFocused();
    await expect(rateInput).toHaveJSProperty('validity.valueMissing', true);
    await expect(page).toHaveURL(/\/teachers\/create$/);
});

test('จัดรูปแบบรหัสอาจารย์ เบอร์โทร และ Line ID', async ({ page }) => {
    await field(page, 'teacher_code').fill('t-ab12!@#');
    await expect(field(page, 'teacher_code')).toHaveValue('T-AB12');

    await field(page, 'phone').fill('081a234b56789');
    await expect(field(page, 'phone')).toHaveValue('081-234-5678');

    await field(page, 'line_id').fill('teacher name@01');
    await expect(field(page, 'line_id')).toHaveValue('teachername01');
});

test('ป้องกัน email ผิดรูปแบบและจำนวนเงินนอกช่วงที่กำหนด', async ({ page }) => {
    await fillRequiredFields(page, 'CLIENT001');
    const emailInput = field(page, 'email');
    const rateInput = field(page, 'rate_amount');

    await emailInput.fill('invalid-email');
    await submit(page);
    await expect(emailInput).toBeFocused();
    await expect(emailInput).toHaveJSProperty('validity.typeMismatch', true);

    await emailInput.clear();
    await rateInput.fill('-1');
    await submit(page);
    await expect(rateInput).toHaveJSProperty('validity.rangeUnderflow', true);

    await rateInput.fill('1000001');
    await submit(page);
    await expect(rateInput).toHaveJSProperty('validity.rangeOverflow', true);
    await expect(page).toHaveURL(/\/teachers\/create$/);
});

test('Laravel ปฏิเสธรหัสอาจารย์และ email ที่ซ้ำ', async ({ page }) => {
    await fillRequiredFields(page, 'DUP001');
    await submit(page);
    await expect(page.getByText('ข้อมูลนี้มีอยู่ในระบบแล้ว')).toBeVisible();

    await field(page, 'teacher_code').fill(`UNIQUE${Date.now()}`);
    await field(page, 'email').fill('existing.teacher@gmail.com');
    await submit(page);
    await expect(page.getByText('ข้อมูลนี้มีอยู่ในระบบแล้ว')).toBeVisible();
    await expect(page).toHaveURL(/\/teachers\/create$/);
});

test('Laravel ปฏิเสธเบอร์สั้น วันเริ่มงานในอนาคต และเวลาสิ้นสุดก่อนเวลาเริ่ม', async ({ page }) => {
    await fillRequiredFields(page, 'SERVER001');
    await field(page, 'phone').fill('12345678');
    await field(page, 'start_date').fill('2099-01-01');
    await field(page, 'availabilities[1][start_time]').fill('18:00');
    await field(page, 'availabilities[1][end_time]').fill('09:00');
    await submit(page);

    const errors = page.locator('.alert-danger li');
    await expect(errors.filter({ hasText: 'เบอร์โทรต้องเป็นตัวเลข 9-10 หลัก' })).toBeVisible();
    await expect(errors.filter({ hasText: 'start date' })).toBeVisible();
    await expect(errors.filter({ hasText: 'end_time' })).toBeVisible();
    await expect(page).toHaveURL(/\/teachers\/create$/);
});

test('ตรวจชนิดและขนาดไฟล์รูปโปรไฟล์ก่อนส่งฟอร์ม', async ({ page }) => {
    const photoInput = field(page, 'photo');
    const photoError = page.locator('#photoError');

    await photoInput.setInputFiles({
        name: 'teacher.txt',
        mimeType: 'text/plain',
        buffer: Buffer.from('not an image'),
    });
    await expect(photoError).toHaveText('รองรับเฉพาะไฟล์ JPG, PNG, WEBP เท่านั้น');
    await expect(photoInput).toHaveValue('');

    await photoInput.setInputFiles({
        name: 'teacher.png',
        mimeType: 'image/png',
        buffer: Buffer.alloc(2 * 1024 * 1024 + 1),
    });
    await expect(photoError).toHaveText('ไฟล์ต้องมีขนาดไม่เกิน 2MB');
    await expect(photoInput).toHaveValue('');
});
