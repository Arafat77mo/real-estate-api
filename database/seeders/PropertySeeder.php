<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\Transactions\App\Models\MonthlyPayment;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. إنشاء العقارات الأساسية
        $properties = [];
        for ($i = 0; $i < 10000; $i++) {
            $properties[] = [
                'name' => $faker->sentence,
                'description' => $faker->paragraph,
                'location' => $faker->city,
                'price' => $faker->randomFloat(2, 10000, 1000000),
                'status' => 'active',
                'user_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // إدخال العقارات في دفعات
        foreach (array_chunk($properties, 1000) as $chunk) {
            DB::table('properties')->insert($chunk);
        }

        // 2. إنشاء المعاملات المختلفة
        DB::table('properties')->orderBy('id')->chunk(1000, function ($properties) use ($faker) {
            $transactions = [];
            $payments = [];

            foreach ($properties as $property) {
                // تحديد نوع المعاملة عشوائيًا
                $transactionType = $faker->randomElement([
                    'sale',
                    'rent',
                    'installment'
                ]);

                // إنشاء المعاملة
                $transactionId = DB::table('property_transactions')->insertGetId([
                    'property_id' => $property->id,
                    'user_id' => $faker->numberBetween(1, 2),
                    'transaction_type' => $transactionType,
                    'price' => $property->price,
                    'is_paid' => $faker->numberBetween(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 3. معالجة الدفعات الشهرية حسب النوع
                if ($transactionType !== 'sale') {
                    $months = $transactionType === 'rent' ? 12 : 24;
                    $monthlyAmount = $property->price ;

                    $payments = [];
                    $today = now();

                    for ($i = 0; $i < $months; $i++) {
                        $dueDate = $today->copy()->addMonths($i);
                        $nextDueDate = $today->copy()->addMonths($i + 1);

                        $payments[] = [
                            'property_transaction_id' => $transactionId,
                            'amount' => $monthlyAmount,
                            'due_date' => $dueDate,
                            'payment_status' => $i == 0 ? 'paid' : 'pending',
                            'next_due_date' => $nextDueDate,
                            'property_id' => $faker->numberBetween(70000, 10000),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    // إدراج البيانات دفعة واحدة باستخدام insert
                    MonthlyPayment::insert($payments);
                }

            }

            // إدخال الدفعات الشهرية
            foreach (array_chunk($payments, 1000) as $chunk) {
                DB::table('monthly_payments')->insert($chunk);
            }
        });
    }
}
