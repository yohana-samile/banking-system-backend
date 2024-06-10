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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['deposit', 'withdrawal', 'transfer']);
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('customer_account_id');
            $table->timestamps();
            $table->foreign('customer_account_id')->references('id')->on('customer_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};
