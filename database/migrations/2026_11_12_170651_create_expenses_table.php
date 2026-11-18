<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Loại chi phí
            $table->string('category');

            // Nội dung chi phí
            $table->string('description')->nullable();

            // Số tiền chi
            $table->decimal('amount', 12, 2);

            // Ngày phát sinh chi phí
            $table->date('expense_date');

            // Nhân viên hoặc quản lý ghi nhận chi phí
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};