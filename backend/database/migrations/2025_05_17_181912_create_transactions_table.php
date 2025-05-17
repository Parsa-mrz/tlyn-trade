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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId ('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId ('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId ('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId ('match_order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal ('weight',10,4);
            $table->decimal ('price_per_gram',20,4);
            $table->decimal ('total_price',20,2);
            $table->bigInteger ('commission')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
