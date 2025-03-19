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
        Schema::create('amazon_review_tasks', function (Blueprint $table) {
            $table->id();
            $table->string("user_id")->nullable();
            $table->string("amazon_review_id")->nullable();
            $table->string("total_task")->nullable();
            $table->string("purchase_id")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_review_tasks');
    }
};
