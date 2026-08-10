# ระบบจัดการข้อมูลอาจารย์ — โรงเรียนดนตรี
(Laravel + Bootstrap 5 + MySQL)

ไฟล์ชุดนี้คือ **ส่วนของแอปพลิเคชัน** (migrations, models, controllers, views, routes, seeders)
ที่ต้องนำไปวางทับใน Laravel project เปล่า เนื่องจากสภาพแวดล้อมที่ใช้สร้างไฟล์นี้ไม่สามารถเข้าถึง
Packagist/Composer เพื่อติดตั้ง Laravel framework เต็มรูปแบบได้

## 1. ติดตั้ง Laravel project เปล่า

```bash
composer create-project laravel/laravel music-school
cd music-school
```

## 2. คัดลอกไฟล์จากชุดนี้ทับเข้าไปในโปรเจกต์

คัดลอกโฟลเดอร์/ไฟล์ต่อไปนี้ทับของเดิม:

```
app/Models/*.php               -> app/Models/
app/Http/Controllers/*.php     -> app/Http/Controllers/
app/Http/Requests/*.php        -> app/Http/Requests/
database/migrations/*.php      -> database/migrations/
database/seeders/*.php         -> database/seeders/
resources/views/*              -> resources/views/
routes/web.php                 -> routes/web.php (แทนที่ไฟล์เดิม)
```

## 3. ตั้งค่าฐานข้อมูลใน `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=music_school
DB_USERNAME=root
DB_PASSWORD=
```

สร้างฐานข้อมูล:
```sql
CREATE DATABASE music_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 4. ติดตั้ง dependency ที่จำเป็น

โปรเจกต์ใช้ Laravel เวอร์ชันมาตรฐาน (ที่มี `Str`, `SoftDeletes` ในตัวอยู่แล้ว) และใช้ Bootstrap 5 ผ่าน CDN
ในไฟล์ `resources/views/layouts/app.blade.php` จึงไม่ต้องติดตั้ง npm package เพิ่ม

สำหรับอัปโหลดรูปภาพอาจารย์ ให้สร้าง symlink storage:
```bash
php artisan storage:link
```

## 5. รัน migration และ seeder

```bash
php artisan migrate
php artisan db:seed
```

seeder จะสร้างข้อมูลตั้งต้น:
- ประเภทอาจารย์ (สอนประจำ / Accompaniment / Workshop)
- เครื่องดนตรี (เปียโน, กีตาร์, กลอง, ไวโอลิน, ขับร้อง ฯลฯ)
- ระดับการสอน (เริ่มต้น / ปานกลาง / สูง / เตรียมสอบ)

## 6. รันเซิร์ฟเวอร์

```bash
php artisan serve
```

เปิดเบราว์เซอร์ที่ `http://127.0.0.1:8000` จะ redirect ไปหน้า `/teachers` โดยอัตโนมัติ

---

## โครงสร้างข้อมูล (Database Design)

| ตาราง | หน้าที่ |
|---|---|
| `teachers` | ข้อมูลอาจารย์หลัก (ชื่อ, ติดต่อ, ประเภทการจ้าง Full-time/Freelance) |
| `teaching_types` | Master: สอนประจำ / Accompaniment / Workshop |
| `instruments` | Master: เครื่องดนตรีที่สอนได้ |
| `levels` | Master: ระดับการสอน |
| `teacher_teaching_type` | Pivot: อาจารย์ ↔ ประเภทการสอน (สอนได้หลายประเภท) |
| `teacher_instrument` | Pivot: อาจารย์ ↔ เครื่องดนตรี (มี `is_primary` ระบุเครื่องดนตรีหลัก) |
| `teacher_level` | Pivot: อาจารย์ ↔ ระดับที่สอนได้ |
| `teacher_rates` | เรทค่าจ้าง — กำหนดแยกตามอาจารย์/ประเภทสอน/เครื่องดนตรี ได้อิสระ (per_hour / per_session / monthly_fixed) |
| `teacher_transport_fees` | ค่ารถ (เหมาต่อวัน หรือ ต่อกิโลเมตร) |
| `teacher_availabilities` | ตารางว่าง (Availability) รายวันในสัปดาห์ |
| `teaching_sessions` | ประวัติ/นัดสอนจริง ใช้คำนวณชั่วโมงสอน + รายได้ย้อนหลัง + ตารางสอนรายวัน/สัปดาห์/เดือน |

## Feature ↔ ไฟล์ที่เกี่ยวข้อง

| Feature | Route | Controller | View |
|---|---|---|---|
| เพิ่ม/แก้ไข/ลบ/ค้นหาอาจารย์ | `teachers.*` (resource) | `TeacherController` | `teachers/index, create, edit, show` |
| กำหนดประเภทอาจารย์ / เครื่องดนตรี / ระดับ | ในฟอร์ม create/edit | `TeacherController@store,update` | `teachers/_form.blade.php` |
| กำหนดเรทค่าจ้าง (แยกตามอาจารย์ได้) | `teachers.rates.store/destroy` | `TeacherRateController` | แท็บ "เรทค่าจ้าง" ใน `teachers/show.blade.php` |
| กำหนดค่ารถ | `teachers.transport-fee.store` | `TeacherRateController@storeTransportFee` | แท็บ "เรทค่าจ้าง" |
| กำหนด Availability | `teachers.availability.update` | `TeacherAvailabilityController` | แท็บ "Availability" |
| ดูประวัติการสอน / ชม.สอน / รายได้ย้อนหลัง | `teachers.sessions.*` | `TeachingSessionController` | แท็บ "ประวัติการสอน" |
| ตารางสอนรายวัน/สัปดาห์/เดือน (รวมทุกอาจารย์) | `schedule.index` | `ScheduleController` | `schedule/index.blade.php` |

## Business Rules ที่รองรับ

1. **Freelance / Full-time** — ฟิลด์ `employment_type` ในตาราง `teachers` และใช้กรองในหน้ารายการ/ค้นหา
2. **รูปแบบค่าจ้างแยกตามอาจารย์** — ตาราง `teacher_rates` ผูกกับ `teacher_id` โดยตรง และยังระบุ
   `teaching_type_id` / `instrument_id` เพิ่มเติมได้ (nullable = ใช้เป็นค่า default ของอาจารย์คนนั้น)
   ทำให้อาจารย์แต่ละคนมีเรทต่างกันได้ทั้งต่อชั่วโมง/ต่อคาบ/เหมาต่อเดือน และมีได้หลายเรทพร้อมกัน
   (เช่น เรทสอนเปียโน ต่างจากเรท Accompaniment)

## หมายเหตุ

- โค้ดชุดนี้ใช้ Bootstrap 5 (CDN) + Bootstrap Icons ไม่ต้อง build asset เพิ่มเติม
- ฟิลด์คำนวณ `hours` และ `income_amount` ใน `teaching_sessions` คำนวณอัตโนมัติผ่าน Eloquent
  model event (`booted()` ใน `TeachingSession.php`) จาก `start_time`/`end_time` และเรทที่ผูกไว้
- ระบบยังไม่รวม authentication/authorization (login, role/permission) — แนะนำให้เพิ่ม
  Laravel Breeze/Jetstream หรือ spatie/laravel-permission ตามความต้องการจริงของโรงเรียน
