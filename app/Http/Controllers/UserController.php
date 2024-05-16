<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Employee;
    use App\Models\Customer;
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Branch;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Database\QueryException;
    use Illuminate\Support\Facades\Hash;
    use DB;

    class UserController extends Controller {
        public function employees(){
            // $employees = Employee::all();
            $employees = DB::select("SELECT users.id, users.name as user_name, users.email, users.phone_number, users.gender, users.address, users.dob, roles.name as role_name, branches.name as branch_name, branches.address as branch_address FROM users, employees, roles, branches WHERE
                employees.user_id = users.id AND
                users.role_id = roles.id AND
                employees.branch_id = branches.id
            ");
            return response()->json($employees);
        }
        public function roles(){
            $roles = Role::take(4)->orderBy('id','desc')->get()->where('name', '!=', 'is_customer');
            return response()->json($roles);
        }

        public function store(Request $request){
            $validatedData = $request->validate([
                'name' =>'required',
                'dob' =>'required',
                'address' =>'required',
                'phone_number' =>'required',
                'role_id' =>'required',
                'branch_id' =>'required',
                'gender' =>'required',
                'password' =>'required',
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

            // create employee record for this user
            $employee = new Employee();
            $employee->branch_id = $validatedData['branch_id'];
            $user->employee()->save($employee); // save it using the hasOne
            // $token = $user->createToken('authToken')->plainTextToken; This work for passport i will try in another project inshaallah

            return response()->json(["success" => "new employee regstered"], 201);
        }

        public function show(Request $request, $id){
            // $employees = Employee::findOrFail($id);
            $employees = DB::select("SELECT users.id, users.name as user_name, users.email, users.phone_number, users.gender, users.address, users.dob, users.created_at, users.updated_at, roles.name as role_name, branches.name as branch_name, branches.address as branch_address
                FROM users, employees, roles, branches WHERE
                employees.user_id = users.id AND
                users.role_id = roles.id AND
                employees.branch_id = branches.id and users.id = '$id'
            ");
            if (!empty($employees)) {
                return response()->json($employees);
            }
            else{
                return response()->json(["error" => "employees not found"]);
            }
        }

        public function update(Request $request, $id){
            $branch = Branch::findOrFail($id);
            $branch->update($request->all());
            return response()->json($branch, 200);
        }

        public function destroy(Request $request, $id){
            $branch = Branch::findOrFail($id);
            $branch->delete($branch);
            return response()->json($branch, 200);
        }

    }


