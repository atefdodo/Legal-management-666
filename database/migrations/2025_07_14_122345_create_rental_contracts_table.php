<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('lessor_name'); // اسم المؤجر
            $table->string('lessee_name'); // اسم المستأجر
            $table->date('contract_date'); // تاريخ تحرير العقد
            $table->date('start_date'); // تاريخ بداية الإيجار
            $table->date('end_date'); // تاريخ نهاية الإيجار
            $table->string('rental_location'); // محل الإيجار
            $table->decimal('rent_amount', 10, 2); // قيمة الإيجار الشهري (بالجنيه)
            $table->string('document_image_path')->nullable(); // مرفق العقد
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_contracts');
    }
};
