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
        Schema::create('amazon_order_accounts', function (Blueprint $table) {
            $table->id();
            $table->string("email")->nullable();
            $table->string("password")->nullable();
            $table->string("name")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("zip_code")->nullable();
            $table->string("number")->nullable();
            $table->string("card_number")->nullable();
            $table->string("month")->nullable();
            $table->string("year")->nullable();
            $table->string("cart1")->nullable();
            $table->string("cart2")->nullable();
            $table->string("free_book")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_order_accounts');
    }
};
