<?php
    namespace Database\Seeders;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use App\Models\Account;

    class AccountSeeder extends Seeder {
        /**
         * Run the database seeds.
         */
        public function run(): void {
            $create_account = Account::create([
                'name' => 'fahari scholer',
                'account_type' => 'Student Accoutn',
            ]);
        }
    }
