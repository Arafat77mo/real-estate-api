<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('rooms')->default(0); // عدد الغرف
            $table->integer('bathrooms')->default(0); // عدد الحمامات
            $table->decimal('living_room_size', 8, 2)->nullable(); // حجم غرفة المعيشة
            $table->text('additional_features')->nullable(); // الميزات الإضافية (اختياري)
            $table->enum('type', ['apartment', 'villa', 'land', 'office', 'commercial']); // نوع العقار
            $table->softDeletes(); // دعم التوثيق الناعم (soft delete)        });

            $table->index('location'); // فهرس لعمود الموقع
            $table->index('status'); // فهرس لعمود الحالة
            $table->index('user_id'); // فهرس لعمود user_id
            $table->index('rooms'); // فهرس لعدد الغرف
            $table->index('bathrooms'); // فهرس لعدد الحمامات
            $table->index('living_room_size'); // فهرس لحجم غرفة المعيشة
            $table->index('type'); // فهرس لنوع العقار

        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            Schema::table('properties', function (Blueprint $table) {
                // حذف الأعمدة إذا تم التراجع عن الهجرة
                $table->dropColumn([
                    'rooms',
                    'bathrooms',
                    'living_room_size',
                    'additional_features',
                    'type',
                    'deleted_at',
                ]);

                $table->dropIndex(['location']);
                $table->dropIndex(['status']);
                $table->dropIndex(['type']);
                $table->dropIndex(['user_id']);
            });
        });
    }
};
