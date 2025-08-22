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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->longText('base64');
            $table->string('title_id')->unique();
            $table->string('base_year');
            $table->string('base_month');
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('low_date')->nullable();
            $table->bigInteger('value');
            $table->string('status');
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
        Schema::dropIfExists('bills');
    }
};
