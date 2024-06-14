<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('customer_transcations', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_type');
                $table->decimal('amount', 10, 2);
                // $table->$table->decimal('amount_deposit', 10, 2)->nullable();
                // $table->$table->decimal('amount_withdraw', 10, 2)->nullable();
                // $table->unsignedBigInteger('actual_balance_id');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('customer_account_id');
                // $table->unsignedBigInteger('user_id');
                $table->string('transaction_performed_by');
                $table->timestamps();
                // $table->foreign('actual_balance_id')->references('id')->on('actual_balances');
                $table->foreign('customer_account_id')->references('id')->on('customer_accounts');
                // $table->foreign('user_id')->references('id')->on('users');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('customer_transcations');
        }
    };
