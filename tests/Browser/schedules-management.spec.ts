import { expect, test, type Page } from '@playwright/test';

const field = (page: Page, name: string) => page.locator(`[name="${name}"]`);
const enrollmentOption = (student: string, course: string) => ({ label: `${student} — ${course}` });

async function openCreate(page: Page): Promise<void> {
    await page.goto('/schedules/create');
    await expect(page.getByRole('heading', { name: 'เพิ่มตารางเรียน' })).toBeVisible();
}

async function selectEnrollment(page: Page, student: string, course: string): Promise<void> {
    await field(page, 'enrollment_id').selectOption(enrollmentOption(student, course));
}

async function fillDateTime(page: Page, date: string, start: string, end: string): Promise<void> {
    await field(page, 'schedule_date').fill(date);
    await field(page, 'start_time').fill(start);
    await field(page, 'end_time').fill(end);
}

test('เพิ่มตารางเรียนหนึ่งคาบสำเร็จพร้อมอาจารย์ ห้อง รูปแบบ และสถานะ', async ({ page }) => {
    await openCreate(page);
    await selectEnrollment(page, 'นักเรียนตาราง A', 'คอร์สปกติ (SCH-REG)');
    await field(page, 'teacher_id').selectOption({ label: 'ครูสอง' });
    await field(page, 'room_id').selectOption({ label: 'ห้องตาราง B (ความจุ 8 คน)' });
    await fillDateTime(page, '2026-09-16', '15:00', '16:00');
    await page.locator('#deliveryModePills .pill-option').filter({ hasText: 'ไฮบริด' }).click();
    await page.locator('#statusPills .pill-option').filter({ hasText: 'นัดสอน' }).click();
    await field(page, 'notes').fill('<b>เตรียมเพลงทดสอบ</b>');
    await page.getByRole('button', { name: 'บันทึกตารางเรียน' }).click();

    await expect(page).toHaveURL(/\/schedules(?:\?.*)?$/);
    await expect(page.getByText('เพิ่มตารางเรียนเรียบร้อยแล้ว')).toBeVisible();
});

test('ตรวจช่องบังคับและเวลาสิ้นสุดต้องหลังเวลาเริ่ม', async ({ page }) => {
    await openCreate(page);
    const enrollment = field(page, 'enrollment_id');
    const start = field(page, 'start_time');
    const end = field(page, 'end_time');

    await page.getByRole('button', { name: 'บันทึกตารางเรียน' }).click();
    await expect(enrollment).toBeFocused();
    await expect(enrollment).toHaveJSProperty('validity.valueMissing', true);

    await selectEnrollment(page, 'นักเรียนตาราง B', 'คอร์สปกติ (SCH-REG)');
    await start.fill('12:00');
    await page.getByRole('button', { name: 'บันทึกตารางเรียน' }).click();
    await expect(end).toBeFocused();
    await expect(end).toHaveJSProperty('validity.valueMissing', true);

    await end.fill('11:00');
    await page.getByRole('button', { name: 'บันทึกตารางเรียน' }).click();
    await expect(page.getByText('เวลาสิ้นสุดต้องอยู่หลังเวลาเริ่ม')).toBeVisible();
    await expect(page).toHaveURL(/\/schedules\/create$/);
});

test('เติมอาจารย์อัตโนมัติและแสดงจำนวนคาบคงเหลือจาก enrollment', async ({ page }) => {
    await openCreate(page);
    await selectEnrollment(page, 'นักเรียนตาราง A', 'คอร์สปกติ (SCH-REG)');
    await expect(field(page, 'teacher_id')).toHaveValue('1');
    await expect(page.locator('#teacherAutoHint')).toContainText('เติมอาจารย์');

    await fillDateTime(page, '2026-09-18', '15:00', '16:00');
    await expect(page.locator('#sessionInfoBox')).toContainText('จัดตารางไปแล้ว');
    await expect(page.locator('#sessionInfoBox')).toContainText('คงเหลือ');
});

test('ตรวจตารางชนของนักเรียน อาจารย์ และห้องแบบ real-time', async ({ page }) => {
    await openCreate(page);
    const conflictBox = page.locator('#conflictBox');

    await selectEnrollment(page, 'นักเรียนตาราง A', 'คอร์สปกติ (SCH-REG)');
    await field(page, 'teacher_id').selectOption({ label: 'ครูสอง' });
    await field(page, 'room_id').selectOption({ label: 'ห้องตาราง B (ความจุ 8 คน)' });
    await fillDateTime(page, '2026-09-15', '10:15', '10:45');
    await expect(conflictBox).toContainText('นักเรียนคนนี้มีตารางเรียนคาบอื่นทับซ้อนเวลาเดียวกันแล้ว');

    await selectEnrollment(page, 'นักเรียนตาราง B', 'คอร์สปกติ (SCH-REG)');
    await field(page, 'teacher_id').selectOption({ label: 'อาจารย์ข้อมูลซ้ำ' });
    await expect(conflictBox).toContainText('อาจารย์ท่านนี้มีตารางสอนคาบอื่นทับซ้อนเวลาเดียวกันแล้ว');

    await field(page, 'teacher_id').selectOption({ label: 'ครูสอง' });
    await field(page, 'room_id').selectOption({ label: 'ห้องตาราง A (ความจุ 4 คน)' });
    await expect(conflictBox).toContainText('ห้องเรียนนี้ถูกจองไว้ทับซ้อนเวลาเดียวกันแล้ว');
    await page.getByRole('button', { name: 'บันทึกตารางเรียน' }).click();
    await expect(page.getByText('ห้องเรียนนี้ถูกจองไว้ทับซ้อนเวลาเดียวกันแล้ว')).toBeVisible();
    await expect(page).toHaveURL(/\/schedules\/create$/);
});

test('ป้องกันการจัดตารางเกินจำนวนครั้งที่ซื้อในแพ็กเกจ', async ({ page }) => {
    await openCreate(page);
    await selectEnrollment(page, 'นักเรียนคอร์สเต็ม', 'คอร์สหนึ่งครั้ง (SCH-FULL)');
    await fillDateTime(page, '2026-10-02', '13:00', '14:00');

    await expect(page.locator('#sessionInfoBox')).toContainText('จัดตารางครบ 1 ครั้งตามแพ็กเกจแล้ว');
    await page.getByRole('button', { name: 'บันทึกตารางเรียน' }).click();
    await expect(page.getByText('คอร์สนี้จัดตารางครบ 1 ครั้งแล้วตามแพ็กเกจที่สมัครไว้')).toBeVisible();
    await expect(page).toHaveURL(/\/schedules\/create$/);
});

test('คอร์สพิเศษจำกัดวันที่ให้อยู่ในช่วงที่กำหนด', async ({ page }) => {
    await openCreate(page);
    await selectEnrollment(page, 'นักเรียนคอร์สพิเศษ', 'คอร์สพิเศษ (SCH-SPC)');
    const date = field(page, 'schedule_date');

    await expect(date).toHaveAttribute('min', '2026-09-01');
    await expect(date).toHaveAttribute('max', '2026-09-03');
    await expect(date).toHaveValue('2026-09-01');
    await expect(page.locator('#courseDateRangeBox')).toContainText('เลือกวันนอกช่วงนี้ไม่ได้');
});

test('แก้ไขตารางและยกเลิกคาบเรียน', async ({ page }) => {
    await page.goto('/schedules/3/edit');
    await expect(page.getByRole('heading', { name: 'แก้ไขตารางเรียน' })).toBeVisible();
    await fillDateTime(page, '2026-09-20', '11:00', '12:00');
    await page.locator('#deliveryModePills .pill-option').filter({ hasText: 'ออนไลน์' }).click();
    await page.locator('#statusPills .pill-option').filter({ hasText: 'ขาดเรียน' }).click();
    await page.getByRole('button', { name: 'บันทึกการแก้ไข' }).click();
    await expect(page.getByText('แก้ไขตารางเรียนเรียบร้อยแล้ว')).toBeVisible();

    await page.goto('/schedules/3/edit');
    page.once('dialog', dialog => dialog.accept());
    await page.getByRole('button', { name: 'ยกเลิกคาบเรียนนี้' }).click();
    await expect(page.getByText('ยกเลิกตารางเรียนคาบนี้เรียบร้อยแล้ว')).toBeVisible();
});

test('จัดตารางรายสัปดาห์แบบชุดและยืนยันบันทึกหลายคาบ', async ({ page }) => {
    await page.goto('/schedules/bulk-create');
    await expect(page.getByRole('heading', { name: 'จัดตารางเรียนแบบชุด' })).toBeVisible();
    await page.locator('#enrollmentSelect').selectOption(enrollmentOption('นักเรียนจัดชุด', 'คอร์สจัดชุด (SCH-BULK)'));
    await page.locator('#startTimeInput').fill('16:00');
    await page.locator('#endTimeInput').fill('17:00');
    await page.locator('#startDateInput').fill('2026-09-07');
    await page.locator('#sessionCountInput').fill('2');
    await page.locator('#dayPills .day-pill').filter({ hasText: 'จันทร์' }).click();
    await page.getByRole('button', { name: 'สร้างตัวอย่างและตรวจสอบ' }).click();

    await expect(page.locator('#summaryTotal')).toHaveText('2');
    await expect(page.locator('#summaryConflict')).toHaveText('0');
    await page.locator('#notesInput').fill('ตารางชุดจาก Playwright');
    await page.getByRole('button', { name: 'ยืนยันบันทึกทั้งหมด' }).click();
    await expect(page.getByText('บันทึกตารางเรียนสำเร็จ 2 คาบ')).toBeVisible();
});

test('แสดงมุมมองรายวันและค้นหาตารางตามนักเรียน', async ({ page }) => {
    await page.goto('/schedules?view=day&date=2026-09-15&q=นักเรียนตาราง+A');
    await expect(page.getByRole('heading', { name: 'ระบบจัดตารางเรียน' })).toBeVisible();
    await expect(page.getByRole('link', { name: 'นักเรียนตาราง A' })).toBeVisible();
    await expect(page.getByText('10:00:00 - 11:00:00', { exact: false })).toBeVisible();

    await page.goto('/schedules?view=month&date=2026-09-15');
    await expect(page.locator('.calendar-grid')).toBeVisible();
    await page.goto('/schedules?view=week&date=2026-09-15');
    await expect(page.locator('.week-grid')).toBeVisible();
});
