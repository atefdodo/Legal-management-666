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
        Schema::create('company_documents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->fulltext()->comment('اسم المستند');
            $table->date('issuance_date')->comment('تاريخ صدور المستند');
            $table->string('issuing_authority')->comment('جهة صدور المستند');
            $table->date('renewal_date')->nullable()->comment('تاريخ تجديد المستند إن وجد');
            $table->string('document_image_path')->nullable()->comment('مسار صورة أو ملف المستند');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_documents');
    }
};
