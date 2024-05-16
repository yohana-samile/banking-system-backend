<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Customer;
    use App\Models\User;
    use App\Models\Role;
    use App\Models\CustomerAccount;
    use App\Models\Branch;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Hash;
    use DB;

    class UserCustomerController extends Controller {
        public function index(){
            // $customers = Customer::all();
            $customers = DB::select("SELECT users.id, users.name as user_name, users.email, users.phone_number, users.gender, users.address, users.dob, roles.name as role_name, accounts.name as account_name, account_types.account_type_name FROM
                users, customers, roles, accounts, account_types WHERE
                customers.user_id = users.id AND
                users.role_id = roles.id AND
                customers.account_id = accounts.id AND
                accounts.account_type_id = account_types.id
            ");
            return response()->json($customers);
        }
        // customers
        public function store(Request $request){
            $validatedData = $request->validate([
                'name' =>'required',
                'dob' =>'required',
                'address' =>'required',
                'phone_number' =>'required',
                'role_id' =>'required',
                'account_id' =>'required',
                'password' =>'required',
                'gender' =>'required',
            ]);
            $nameParts = explode(' ', $validatedData['name']);
            $username = strtolower($nameParts[0]);
            $domain = '';
            if (count($nameParts) > 1) {
                $domain = strtolower(implode('', array_slice($nameParts, 1)));
            }
            $generated_email = $username . '@' . $domain . '.bank.co.tz';
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $generated_email,
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'role_id' => $validatedData['role_id'],
                'dob' => $validatedData['dob'],
                'gender' => $validatedData['gender'],
                'password' => Hash::make($validatedData['password']),
            ]);
            // create Customer record for this user
            $customer = new Customer();
            $customer->account_id = $validatedData['account_id'];
            $user->customer()->save($customer); // save it using the hasOne

            // create account number
            $customer_account_number = rand(100000, 300000);
            // Insert data into customer_account table
            $account_number = new CustomerAccount();
            $account_number->account_number = $customer_account_number;
            $account_number->customer_id = $customer->id; // Set the foreign key manually
            $account_number->save();
            return response()->json(["success" => "new customer registered"], 201);
        }

        public function show(Request $request, $id){
            $employees = DB::select("SELECT users.created_at, users.updated_at, users.name as user_name, users.email, users.phone_number, users.gender, users.address, users.dob, roles.name as role_name, accounts.name as account_name, account_types.account_type_name, customer_accounts.account_number FROM
                users, customers, roles, accounts, account_types, customer_accounts WHERE
                customers.user_id = users.id AND
                users.role_id = roles.id AND
                accounts.account_type_id = account_types.id AND
                customer_accounts.customer_id = customers.id AND
                customers.account_id = accounts.id AND users.id = '$id'
            ");
            if (!empty($employees)) {
                return response()->json($employees);
            }
            else{
                return response()->json(["error" => "employees not found"]);
            }
        }

    }
