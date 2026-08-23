import { expect, test } from '@playwright/test';

const adminPages = [
    { name: 'แดชบอร์ด', path: '/dashboard' },
    { name: 'การแจ้งเตือน', path: '/notifications' },
    { name: 'รายการอาจารย์', path: '/teachers' },
    { name: 'เพิ่มอาจารย์', path: '/teachers/create' },
    { name: 'รายการคอร์ส', path: '/courses' },
    { name: 'เพิ่มคอร์ส', path: '/courses/create' },
    { name: 'คูปองและโปรโมชั่น', path: '/coupons' },
    { name: 'รายการนักเรียน', path: '/students' },
    { name: 'เพิ่มนักเรียน', path: '/students/create' },
    { name: 'ผู้ปกครอง', path: '/guardians' },
    { name: 'รายการห้องเรียน', path: '/rooms' },
    { name: 'เพิ่มห้องเรียน', path: '/rooms/create' },
    { name: 'ตารางการใช้ห้อง', path: '/rooms-schedule' },
    { name: 'รายการขายคอร์ส', path: '/sales' },
    { name: 'ขายคอร์ส', path: '/sales/create' },
    { name: 'รายการเปลี่ยนคอร์ส', path: '/course-transfers' },
    { name: 'สร้างรายการเปลี่ยนคอร์ส', path: '/course-transfers/create' },
    { name: 'รายการตารางเรียน', path: '/schedules' },
    { name: 'เพิ่มตารางเรียน', path: '/schedules/create' },
    { name: 'จัดตารางแบบชุด', path: '/schedules/bulk-create' },
    { name: 'ตารางเรียนรวม', path: '/schedule' },
    { name: 'คำขอลาหยุดของอาจารย์', path: '/teacher-leaves' },
    { name: 'คำขอเรียนชดเชย', path: '/makeup-requests' },
    { name: 'คำขอเปลี่ยนตาราง', path: '/reschedule-requests' },
    { name: 'สร้างคำขอเปลี่ยนตาราง', path: '/reschedule-requests/create' },
    { name: 'บันทึกการสอน', path: '/teaching-logs' },
    { name: 'ตรวจการบ้าน', path: '/homework-submissions' },
    { name: 'Run Through', path: '/run-throughs' },
    { name: 'หมวดหมู่ประเมินผล', path: '/evaluation-categories' },
    { name: 'เงินเดือน', path: '/payroll' },
    { name: 'ค่ารถอาจารย์', path: '/transport-fees' },
    { name: 'หมวดหมู่สินค้า', path: '/product-categories' },
    { name: 'รายการสินค้า', path: '/products' },
    { name: 'เพิ่มสินค้า', path: '/products/create' },
    { name: 'สต็อกสินค้า', path: '/stock' },
    { name: 'รายการขายหน้าร้าน', path: '/store-sales' },
    { name: 'ขายสินค้าหน้าร้าน', path: '/store-sales/create' },
    { name: 'ผู้ใช้งานระบบ', path: '/users' },
    { name: 'เพิ่มผู้ใช้งาน', path: '/users/create' },
] as const;

for (const adminPage of adminPages) {
    test(`หน้า ${adminPage.name} เปิดได้โดยไม่มี server error`, async ({ page }) => {
        const response = await page.goto(adminPage.path);

        expect(response, `ไม่มี HTTP response จาก ${adminPage.path}`).not.toBeNull();
        expect(response!.status(), `${adminPage.path} ตอบกลับ ${response!.status()}`).toBeLessThan(500);
        await expect(page).not.toHaveURL(/\/login$/);
        await expect(page.locator('body')).not.toContainText('Server Error');
    });
}

test('ผู้ใช้ที่ยังไม่ล็อกอินถูกส่งกลับหน้า login จากทุกกลุ่มสิทธิ์', async ({ browser }) => {
    const context = await browser.newContext({ storageState: { cookies: [], origins: [] } });
    const page = await context.newPage();
    const protectedPages = ['/dashboard', '/teachers', '/teaching-logs', '/my-leaves', '/my-teacher-leave', '/store'];

    for (const path of protectedPages) {
        await page.goto(path);
        await expect(page).toHaveURL(/\/login$/);
    }

    await context.close();
});
