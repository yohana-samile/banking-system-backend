<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('accounts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('account_for');
                $table->unsignedBigInteger('account_type_id');
                $table->timestamps();
                $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('accounts');
        }
    };

