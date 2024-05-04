<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Customer;
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Branch;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Hash;
    use DB;

    class UserCustomerController extends Controller {
        public function index(){
            // $customers = Customer::all();
            $customers = DB::select("
                SELECT users.id, users.name as user_name, users.email, users.phone_number, users.gender, users.address, users.dob, roles.name as role_name, accounts.name as account_name, accounts.account_type FROM users, customers, roles, accounts WHERE
                customers.user_id = users.id AND
                users.role_id = roles.id AND
                customers.account_id = accounts.id
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
            return response()->json(["success" => "new branch created"], 201);
        }

        public function show(Request $request, $id){
            $employees = DB::select("SELECT users.created_at, users.updated_at, users.name as user_name, users.email, users.phone_number, users.gender, users.address, users.dob, roles.name as role_name, accounts.name as account_name, accounts.account_type
                FROM users, customers, roles, accounts WHERE
                customers.user_id = users.id AND
                users.role_id = roles.id AND
                customers.account_id = accounts.id and users.id = '$id'
            ");
            if (!empty($employees)) {
                return response()->json($employees);
            }
            else{
                return response()->json(["error" => "employees not found"]);
            }
        }

    }
