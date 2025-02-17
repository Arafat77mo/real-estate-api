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
        Schema::create('property_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade'); // العقار
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم
            $table->enum('transaction_type', ['sale', 'rent', 'installment']); // نوع المعاملة
            $table->string('duration_months')->nullable();
            $table->decimal('price', 10, 2); // السعر
            $table->boolean('is_paid')->default(false); // حالة الدفع
            $table->timestamps();
        });

        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_transaction_id')->constrained()->onDelete('cascade'); // ربط القسط بالمعاملة
            $table->decimal('amount', 10, 2); // قيمة القسط
            $table->boolean('is_paid')->default(false); // حالة القسط
            $table->date('due_date'); // تاريخ استحقاق القسط
            $table->timestamps();
        });


        Schema::create('monthly_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_transaction_id')->constrained()->onDelete('cascade'); // ربط الدفع بالمعاملة
            $table->unsignedBigInteger('property_id');
            $table->decimal('amount', 10, 2); // القيمة الشهرية
            $table->date('due_date'); // تاريخ استحقاق الدفع
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending'); // حالة الدفع
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_transactions');
    }
};
