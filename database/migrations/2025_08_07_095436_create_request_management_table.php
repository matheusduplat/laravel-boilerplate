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
        Schema::create('request_management', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('type');
            $table->longText('note_customer');
            $table->longText('location_performing_procedure')->nullable();
            $table->string('attachment')->nullable();
            $table->string('code_guide')->nullable();
            $table->string('status');
            $table->string('status_procedure')->nullable();
            $table->timestamp('date_close')->nullable();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_management');
    }
};
