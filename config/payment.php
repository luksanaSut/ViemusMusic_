<?php

return [
    // เลข PromptPay ของโรงเรียน (เบอร์โทร หรือ เลขผู้เสียภาษีนิติบุคคล 13 หลัก) — ใช้สร้าง QR
    'promptpay_id' => env('PROMPTPAY_ID', ''),

    'bank_name'         => env('BANK_NAME', 'ธนาคารกสิกรไทย'),
    'bank_account_name' => env('BANK_ACCOUNT_NAME', 'บริษัท ตัวอย่าง จำกัด'),
    'bank_account_no'   => env('BANK_ACCOUNT_NO', ''),
];
