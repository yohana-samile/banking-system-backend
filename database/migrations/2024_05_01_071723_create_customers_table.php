<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                // $table->string('first_name');
                // $table->string('middle_name');
                // $table->string('surname'); this three attribute i replaced them by attribute name in the user table
                $table->boolean('account_validation')->default(true);
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('account_id');
                $table->timestamps();
                $table->foreign('account_id')->references('id')->on('accounts');
                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('customers');
        }
    };

