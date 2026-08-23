import { Buffer } from 'node:buffer';
import { expect, test, type Locator, type Page } from '@playwright/test';

const field = (page: Page, name: string): Locator => page.locator(`[name="${name}"]`);
const card = (page: Page, group: string, value: string): Locator =>
    page.locator(`.${group}-card[data-value="${value}"]`);

async function openCreate(page: Page): Promise<void> {
    await page.goto('/courses/create');
    await expect(page.getByRole('heading', { name: 'เพิ่มคอร์สเรียน' })).toBeVisible();
}

async function fillRegularRequired(page: Page, code: string, name = 'คอร์ส Playwright'): Promise<void> {
    await field(page, 'course_code').fill(code);
    await field(page, 'name').fill(name);
    await field(page, 'total_sessions').fill('12');
    await field(page, 'duration_months').fill('3');
    await field(page, 'price').fill('4500');
}

async function submitCreate(page: Page): Promise<void> {
    await page.locator('form[action$="/courses"] button.btn-accent').click();
}

async function selectInstrument(page: Page, name: string): Promise<void> {
    await page.locator('#courseInstrumentSearch').fill(name);
    await page.getByRole('button', { name, exact: true }).click();
}

test.beforeEach(async ({ page }) => {
    await openCreate(page);
});

test('เพิ่มคอร์ส Regular Private พร้อมข้อมูล สิทธิ์ และอาจารย์สำเร็จ', async ({ page }) => {
    const code = `REG${Date.now()}`;
    await fillRegularRequired(page, code, 'คอร์ส Private Playwright');
    await field(page, 'description').fill('<b>รายละเอียดสำหรับทดสอบ</b>');
    await selectInstrument(page, 'เปียโน');
    await field(page, 'level_id').selectOption({ label: 'เริ่มต้น (Beginner)' });
    await card(page, 'delivery', 'hybrid').click();
    await field(page, 'emergency_leave_quota').fill('2');
    await page.locator('input[type="checkbox"][name="is_adult_flexi"]').check();
    await page.getByLabel('อาจารย์ข้อมูลซ้ำ').check();
    await field(page, 'image').setInputFiles({
        name: 'course.png',
        mimeType: 'image/png',
        buffer: Buffer.from(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            'base64',
        ),
    });
    await expect(page.locator('#coursePhotoImg')).toBeVisible();
    await submitCreate(page);

    await expect(page).toHaveURL(/\/courses(?:\?.*)?$/);
    await expect(page.getByText('เพิ่มคอร์สเรียนเรียบร้อยแล้ว')).toBeVisible();
    const row = page.locator('tbody tr').filter({ hasText: code });
    await expect(row).toContainText('คอร์ส Private Playwright');
    await expect(row).toContainText('เปียโน');
    await expect(row).toContainText('Private');
    await expect(row).toContainText('Adult Flexi');
    await expect(row.locator('img')).toBeVisible();
});

test('ตรวจช่องบังคับของคอร์ส Regular', async ({ page }) => {
    const codeInput = field(page, 'course_code');
    const nameInput = field(page, 'name');
    const sessionsInput = field(page, 'total_sessions');
    const durationInput = field(page, 'duration_months');
    const priceInput = field(page, 'price');

    await submitCreate(page);
    await expect(codeInput).toBeFocused();
    await expect(codeInput).toHaveJSProperty('validity.valueMissing', true);

    await codeInput.fill('REQ-COURSE');
    await submitCreate(page);
    await expect(nameInput).toBeFocused();

    await nameInput.fill('คอร์สตรวจช่องบังคับ');
    await submitCreate(page);
    await expect(priceInput).toBeFocused();

    await priceInput.fill('4500');
    await submitCreate(page);
    await expect(page.locator('.alert-danger li')).toHaveCount(2);
    await expect(sessionsInput).toHaveValue('');
    await expect(durationInput).toHaveValue('');
    await expect(page).toHaveURL(/\/courses\/create$/);
});

test('Laravel เปลี่ยนรหัสเป็นตัวพิมพ์ใหญ่ ตัด HTML และปฏิเสธรหัสซ้ำ', async ({ page }) => {
    await fillRegularRequired(page, 'normalize-01', '  <b>คอร์ส Normalize</b>  ');
    await submitCreate(page);
    await expect(page.getByText('เพิ่มคอร์สเรียนเรียบร้อยแล้ว')).toBeVisible();
    await expect(page.locator('tbody tr').filter({ hasText: 'NORMALIZE-01' })).toContainText('คอร์ส Normalize');

    await openCreate(page);
    await fillRegularRequired(page, 'SCH-REG', 'คอร์สรหัสซ้ำ');
    await submitCreate(page);
    await expect(page.getByText('ข้อมูลนี้มีอยู่ในระบบแล้ว')).toBeVisible();
    await expect(page).toHaveURL(/\/courses\/create$/);
});

test('สลับประเภทแล้วแสดงฟิลด์และคำอธิบายที่สัมพันธ์กัน', async ({ page }) => {
    await expect(page.locator('#regularFieldsBox')).toBeVisible();
    await expect(page.locator('#specialFieldsBox')).toBeHidden();
    await expect(field(page, 'max_students')).toBeDisabled();
    await expect(page.locator('#capacityHint')).toHaveText('Private = ไม่จำกัด/ไม่มีเต็ม');

    await card(page, 'class', 'group').click();
    await expect(field(page, 'max_students')).toBeEnabled();
    await expect(field(page, 'max_students')).toHaveValue('2');
    await expect(page.locator('#capacityHint')).toHaveText('Group กำหนดได้มากกว่า 1');

    await card(page, 'structure', 'special').click();
    await card(page, 'class', 'special_activity').click();
    await expect(page.locator('#regularFieldsBox')).toBeHidden();
    await expect(page.locator('#specialFieldsBox')).toBeVisible();
    await expect(page.locator('#deliveryModeBox')).toBeHidden();
    await expect(page.locator('#activityTypeBox')).toBeVisible();
    await expect(page.locator('#capacityHint')).toHaveText('เต็มตามจำนวนที่กำหนดของกิจกรรม');
});

test('ตรวจค่าต่ำสุดและสูงสุดของจำนวนครั้ง ระยะเวลา ราคา ความจุ และโควตาลา', async ({ page }) => {
    await fillRegularRequired(page, 'LIMIT-COURSE');
    const sessions = field(page, 'total_sessions');
    const duration = field(page, 'duration_months');
    const price = field(page, 'price');
    const quota = field(page, 'emergency_leave_quota');

    await sessions.fill('0');
    await submitCreate(page);
    await expect(sessions).toHaveJSProperty('validity.rangeUnderflow', true);
    await sessions.fill('501');
    await expect(sessions).toHaveJSProperty('validity.rangeOverflow', true);

    await sessions.fill('12');
    await duration.fill('37');
    await expect(duration).toHaveJSProperty('validity.rangeOverflow', true);
    await duration.fill('3');
    await price.fill('1000001');
    await expect(price).toHaveJSProperty('validity.rangeOverflow', true);
    await price.fill('-1');
    await expect(price).toHaveJSProperty('validity.rangeUnderflow', true);
    await price.fill('4500');
    await quota.fill('11');
    await expect(quota).toHaveJSProperty('validity.rangeOverflow', true);
    await quota.fill('1');

    await card(page, 'class', 'group').click();
    const capacity = field(page, 'max_students');
    await capacity.fill('1');
    await expect(capacity).toHaveJSProperty('validity.rangeUnderflow', false);
    await submitCreate(page);
    await expect(page.getByText('คอร์สแบบ Group ต้องกำหนดจำนวนผู้เรียนสูงสุดอย่างน้อย 2 คน')).toBeVisible();
});

test('เพิ่มคอร์ส Group พร้อมความจุและรูปแบบออนไลน์สำเร็จ', async ({ page }) => {
    const code = `GROUP${Date.now()}`;
    await fillRegularRequired(page, code, 'คอร์ส Group Playwright');
    await card(page, 'class', 'group').click();
    await card(page, 'delivery', 'online').click();
    await field(page, 'max_students').fill('8');
    await field(page, 'duration_months').fill('6');
    await expect(page.locator('#extensionHint')).toHaveText('สิทธิ์ขยายเวลาอัตโนมัติ: ขยายได้ 2 เดือน');
    await submitCreate(page);

    const row = page.locator('tbody tr').filter({ hasText: code });
    await expect(row).toContainText('Group');
    await expect(row).toContainText('8');
});

test('เพิ่ม Special Activity สำเร็จและตรวจวันที่สิ้นสุดก่อนวันที่เริ่ม', async ({ page }) => {
    const code = `SPECIAL${Date.now()}`;
    await field(page, 'course_code').fill(code);
    await field(page, 'name').fill('Workshop Playwright');
    await card(page, 'structure', 'special').click();
    await card(page, 'class', 'special_activity').click();
    await card(page, 'activity', 'workshop').click();
    await field(page, 'days_count').fill('3');
    await field(page, 'hours_per_day').fill('2.5');
    await field(page, 'course_start_date').fill('2026-11-10');
    await field(page, 'course_end_date').fill('2026-11-09');
    await field(page, 'price').fill('3200');
    await field(page, 'max_students').fill('20');
    await submitCreate(page);
    await expect(page.getByText('วันที่สิ้นสุดต้องไม่ก่อนวันที่เริ่ม')).toBeVisible();

    await field(page, 'course_end_date').fill('2026-11-12');
    await submitCreate(page);
    await expect(page.getByText('เพิ่มคอร์สเรียนเรียบร้อยแล้ว')).toBeVisible();
    const row = page.locator('tbody tr').filter({ hasText: code });
    await expect(row).toContainText('Special Activity');
    await expect(row).toContainText('Workshop');
    await expect(row).toContainText('20');
});

test('Special Activity ต้องระบุประเภทย่อยและจำนวนผู้เข้าร่วม', async ({ page }) => {
    await field(page, 'course_code').fill('SPECIAL-REQUIRED');
    await field(page, 'name').fill('กิจกรรมที่ข้อมูลไม่ครบ');
    await card(page, 'structure', 'special').click();
    await card(page, 'class', 'special_activity').click();
    await field(page, 'days_count').fill('1');
    await field(page, 'hours_per_day').fill('1');
    await field(page, 'course_start_date').fill('2026-12-01');
    await field(page, 'course_end_date').fill('2026-12-01');
    await field(page, 'price').fill('500');
    await field(page, 'max_students').clear();
    await submitCreate(page);

    await expect(page.getByText('คอร์สแบบ Special Activity ต้องกำหนดจำนวนผู้เข้าร่วมสูงสุด')).toBeVisible();
    await expect(page).toHaveURL(/\/courses\/create$/);
});

test('เพิ่มเครื่องดนตรีใหม่แบบ inline แล้วเลือกใช้กับคอร์สได้', async ({ page }) => {
    const instrument = `เครื่องทดสอบ ${Date.now()}`;
    await page.locator('#courseInstrumentSearch').fill(instrument);
    await page.getByRole('button', { name: `+ เพิ่ม "${instrument}" เป็นเครื่องดนตรีใหม่` }).click();
    await expect(page.locator('#courseInstrumentChip')).toContainText(instrument);
    await expect(field(page, 'instrument_id')).not.toHaveValue('');

    await fillRegularRequired(page, `INLINE${Date.now()}`, 'คอร์สเครื่องดนตรีใหม่');
    await submitCreate(page);
    await expect(page.locator('tbody tr').filter({ hasText: 'คอร์สเครื่องดนตรีใหม่' })).toContainText(instrument);
});

test('แก้ไข เปิด-ปิด ค้นหา กรอง และลบคอร์สพร้อมยืนยัน', async ({ page }) => {
    const code = `LIFE${Date.now()}`;
    await fillRegularRequired(page, code, 'คอร์ส Lifecycle เดิม');
    await submitCreate(page);

    let row = page.locator('tbody tr').filter({ hasText: code });
    await row.getByRole('link').click();
    await expect(page.getByRole('heading', { name: 'แก้ไขคอร์สเรียน: คอร์ส Lifecycle เดิม' })).toBeVisible();
    await field(page, 'name').fill('คอร์ส Lifecycle แก้ไขแล้ว');
    await field(page, 'is_active').selectOption('0');
    await page.getByRole('button', { name: 'บันทึกการแก้ไข' }).click();
    await expect(page.getByText('แก้ไขคอร์สเรียนเรียบร้อยแล้ว')).toBeVisible();

    await field(page, 'q').fill(code);
    await field(page, 'class_type').selectOption('private');
    await field(page, 'is_active').selectOption('0');
    await page.locator('form[method="GET"] button').click();
    row = page.locator('tbody tr').filter({ hasText: code });
    await expect(row).toContainText('คอร์ส Lifecycle แก้ไขแล้ว');
    await expect(row).toContainText('ปิดใช้งาน');

    await row.getByRole('button', { name: 'ปิดใช้งาน' }).click();
    await expect(page.getByText(`เปิดใช้งานคอร์ส "คอร์ส Lifecycle แก้ไขแล้ว" แล้ว`)).toBeVisible();

    await field(page, 'is_active').selectOption('1');
    await page.locator('form[method="GET"] button').click();
    row = page.locator('tbody tr').filter({ hasText: code });
    page.once('dialog', dialog => dialog.accept());
    const deleteButton = row.locator('button.btn-outline-danger');
    await deleteButton.click();
    await expect(page.getByText('ลบคอร์สเรียนเรียบร้อยแล้ว')).toBeVisible();
    await expect(page.locator('tbody tr').filter({ hasText: code })).toHaveCount(0);
});
