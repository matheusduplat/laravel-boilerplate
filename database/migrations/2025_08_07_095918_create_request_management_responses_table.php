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
        Schema::create('request_management_responses', function (Blueprint $table) {
            $table->id();
            $table->boolean('hidden')->default(false);
            $table->morphs('authorable');
            $table->longText('answer');
            $table->timestamp('date_hour');
            $table->foreignId('request_management_id')->constrained('request_management');
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
        Schema::dropIfExists('request_management_responses');
    }
};
