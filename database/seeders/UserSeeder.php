<?php
    namespace Database\Seeders;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use DB;
    use Illuminate\Support\Facades\Hash;

    class UserSeeder extends Seeder {
        /**
         * Run the database seeds.
         */
        public function run(): void {
            DB::table('users')->insert([
                'name' => 'Developer Samile',
                'gender' => 'male',
                'address' => 'p.o.box 1 mzumbe',
                'phone_number' => '0620350083',
                'dob' => '2024-04-05',
                'email' => 'yohanasamile@gmail.com',
                'role_id' => 1,
                'password' => Hash::make('12345678'),
            ]);
        }
    }

