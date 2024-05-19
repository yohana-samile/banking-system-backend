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
        Schema::create('bank_balances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('balance');
            $table->string('status')->default('unused');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_balances');
    }
};
