<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\Level;
use App\Models\TeachingType;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ประเภทอาจารย์
        $types = [
            ['name' => 'สอนประจำ',      'code' => 'regular'],
            ['name' => 'Accompaniment', 'code' => 'accompaniment'],
            ['name' => 'Workshop',      'code' => 'workshop'],
        ];
        foreach ($types as $t) {
            TeachingType::firstOrCreate(['code' => $t['code']], $t);
        }

        // เครื่องดนตรี
        $instruments = [
            'เปียโน', 'กีตาร์', 'กีตาร์ไฟฟ้า', 'เบส', 'กลองชุด',
            'ไวโอลิน', 'เชลโล', 'ขับร้อง (Vocal)', 'ขลุ่ย', 'ซอ', 'คีย์บอร์ด', 'ยูคูเลเล่',
        ];
        foreach ($instruments as $name) {
            Instrument::firstOrCreate(['name' => $name]);
        }

        // ระดับการสอน
        $levels = [
            ['name' => 'เริ่มต้น (Beginner)',        'sort_order' => 1],
            ['name' => 'ปานกลาง (Intermediate)',     'sort_order' => 2],
            ['name' => 'ระดับสูง (Advanced)',         'sort_order' => 3],
            ['name' => 'เตรียมสอบ / เตรียมแข่งขัน',   'sort_order' => 4],
        ];
        foreach ($levels as $l) {
            Level::firstOrCreate(['name' => $l['name']], $l);
        }
    }
}
