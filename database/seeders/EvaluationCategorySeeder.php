<?php

namespace Database\Seeders;

use App\Models\EvaluationCategory;
use Illuminate\Database\Seeder;

class EvaluationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['เทคนิคการเล่น', 'จังหวะและความแม่นยำ', 'ทฤษฎีดนตรี', 'การแสดงออกทางดนตรี', 'ความตั้งใจและวินัย'];

        foreach ($categories as $i => $name) {
            EvaluationCategory::firstOrCreate(['name' => $name], ['sort_order' => $i, 'is_active' => true]);
        }
    }
}