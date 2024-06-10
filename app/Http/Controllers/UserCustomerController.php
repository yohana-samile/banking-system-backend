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
            $employees = DB::select("SELECT users.created_at, users.updated_at, users.name as user_name, users.email, users.phone_number, users.gender, users.address, users.dob, roles.name as role_name, accounts.name as account_name, account_types.account_type_name, customer_accounts.id, customer_accounts.account_number FROM
                users, customers, roles, accounts, account_types, customer_accounts WHERE
                customers.user_id = users.id AND
                users.role_id = roles.id AND
                accounts.account_type_id = account_types.id AND
                customer_accounts.customer_id = customers.id AND
                customers.account_id = accounts.id AND
                users.id = '$id'
            ");
            if (!empty($employees)) {
                return response()->json($employees);
            }
            else{
                return response()->json(["error" => "employees not found"]);
            }
        }

        // total customers
        public function totalCustomers(){
            $result = DB::select("SELECT COUNT(id) as totalCustomers FROM `customers` ");
            if (!empty($result)) {
                $customer = $result[0]->totalCustomers;
            } else {
                $customer = 0;
            }
            return response()->json(['totalCustomers' => $customer]);
        }

        public function myAccountDetails($id){
            $accountDetails = DB::select("SELECT transaction_histories.transaction_type, transaction_histories.amount, transaction_histories.description, transaction_histories.created_at, customer_accounts.customer_id, customer_accounts.account_number, accounts.name, account_types.account_type_name FROM
                customer_accounts, accounts, account_types, transaction_histories WHERE
                accounts.account_type_id = account_types.id AND
                transaction_histories.customer_account_id = customer_accounts.id AND
                customer_accounts.id = '$id'
            ");
            if (!empty($accountDetails)) {
                return response()->json($accountDetails);
            }
            else{
                return response()->json(["error" => "account details not found"]);
            }
        }

        // request new account
        public function requestNewAccount(Request $request){
            $data = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'account_type' => 'required|integer|exists:accounts,id',
            ]);

            $account_type_requested = $data['account_type'];
            $validate_account = DB::table('customers')
                ->where('user_id', $data['user_id'])
                ->where('account_id', $account_type_requested)
                ->first();
            if ($validate_account) {
                return response()->json(["error" => "Already having this account"], 400);
            }
            DB::beginTransaction();

            try {
                // Create new customer record
                $customer = new Customer();
                $customer->account_id = $data['account_type'];
                $customer->account_validation = 0;
                $customer->user_id = $data['user_id'];
                $customer->save();

                // Create account number
                $customer_account_number = rand(200000, 300000);

                // Insert data into customer_account table
                $account_number = new CustomerAccount();
                $account_number->account_number = $customer_account_number;
                $account_number->customer_id = $customer->id; // Set the foreign key manually
                $account_number->save();

                DB::commit();

                return response()->json(["success" => "Account created successfully"], 201);
            }
            catch (\Exception $e) {
                DB::rollBack();
                return response()->json(["error" => "Failed to create account", "details" => $e->getMessage()], 500);
            }
        }

        //returnRequest
        public function returnRequest(){
            $accountRequest = DB::select("SELECT customers.id, users.name, customers.account_validation, customer_accounts.account_number FROM
                customers, users, customer_accounts WHERE
                customers.user_id = users.id AND
                customer_accounts.customer_id = customers.id AND
                customers.account_validation = 0
            ");
            if (!empty($accountRequest)) {
                return response()->json($accountRequest);
            }
            else{
                return response()->json(["error" => "details not found"]);
            }
        }

        // approveRequestAccount
        public function approveRequestAccount(Request $request, $id){
            $customer = Customer::findOrFail($id);
            $customer->update($request->all());
            if ($customer) {
                return response()->json($customer, 200);
            }
            else{
                return response()->json(["error" => "details not found"]);
            }
        }
    }
