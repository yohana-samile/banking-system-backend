<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('actual_balances', function (Blueprint $table) {
                $table->id();
                $table->decimal('balance', 10, 2);
                $table->unsignedBigInteger('customer_account_id');
                // $table->unsignedBigInteger('user_id');
                $table->timestamps();
                $table->foreign('customer_account_id')->references('id')->on('customer_accounts');
                // $table->foreign('user_id')->references('id')->on('users');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('actual_balances');
        }
    };
