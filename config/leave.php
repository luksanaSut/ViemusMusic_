<?php

return [
    // ต้องแจ้งลาปกติ/ลาไม่ชดเชยล่วงหน้าอย่างน้อยกี่ชั่วโมง (ลาฉุกเฉินได้รับการยกเว้น)
    'normal_advance_notice_hours' => env('LEAVE_ADVANCE_NOTICE_HOURS', 24),
];
