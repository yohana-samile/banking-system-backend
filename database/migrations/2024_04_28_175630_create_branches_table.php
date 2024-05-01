<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
        */
        public function up(): void {
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->string('branch_number')->unique();
                $table->string('country');
                $table->string('city');
                $table->string('district');
                $table->string('zip_code');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('branches');
        }
    };
