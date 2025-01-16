<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // يمكنك استخدام الودائع (batch inserts) لزيادة الكفاءة.
        $properties = [];

        for ($i = 0; $i < 1000000; $i++) {
            $properties[] = [
                'name' => $faker->sentence,
                'description' => $faker->paragraph,
                'location' => $faker->city,
                'price' => $faker->randomFloat(2, 10000, 1000000),
                'status' => $faker->randomElement(['inactive','active']),
                'user_id' => rand(1, 2), // افترض وجود مستخدمين بقيم معرفات من 1 إلى 10
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // إذا كانت الذاكرة تنفد، يمكنك إدخال السجلات في دفعات (مثال: 1000 سجل في كل مرة)
            if (count($properties) >= 1000) {
                DB::table('properties')->insert($properties);
                $properties = []; // إعادة تعيين المصفوفة
            }
        }

        // إدخال السجلات المتبقية
        if (count($properties) > 0) {
            DB::table('properties')->insert($properties);
        }
    }
}
