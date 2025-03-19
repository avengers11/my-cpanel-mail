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
        Schema::create('amazon_reviews', function (Blueprint $table) {
            $table->id();
            $table->string("user_id")->nullable();
            $table->text("book_name")->nullable();
            $table->text("book_image")->nullable();
            $table->text("book_url")->nullable();
            $table->string("frequency")->nullable();
            $table->string("type")->nullable();
            $table->string("total_review")->nullable();
            $table->string("purchase_completed")->nullable();
            $table->text("purchase_text")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_reviews');
    }
};
