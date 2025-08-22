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
        Schema::create('digital_wallets', function (Blueprint $table) {
            $table->id();
            $table->json('plan');
            $table->longText('qr_code_verification');
            $table->string('number');
            $table->date('date_issue');
            $table->date('validity');
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
        Schema::dropIfExists('digital_wallets');
    }
};
