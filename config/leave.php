<?php

return [
    // ต้องแจ้งลาปกติ/ลาไม่ชดเชยล่วงหน้าอย่างน้อยกี่ชั่วโมง (ลาฉุกเฉินได้รับการยกเว้น)
    'normal_advance_notice_hours' => env('LEAVE_ADVANCE_NOTICE_HOURS', 24),

    // ถ้าวันที่ขอเรียนชดเชยห่างจากวันลาเกินกี่วัน ถือว่า "เกินกำหนด" ต้องแจ้งแอดมินเป็นพิเศษ
    'makeup_validity_days' => env('LEAVE_MAKEUP_VALIDITY_DAYS', 30),
];
