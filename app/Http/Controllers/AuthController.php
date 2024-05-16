<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
    use App\Models\Employee;
    use Illuminate\Support\Facades\Validator;
    use App\Models\Customer;

    class AuthController extends Controller {
        public function __construct() {
            $this->middleware('auth:api', ['except' => ['login','register']]);
            // $this->middleware('auth:api', ['except' => ['login']]);
        }

        /* Login API */
        public function login(Request $request) {
            $validator = Validator::make($request->all(),
                [
                    'email'=>'required|string|email',
                    'password'=>'required|string'
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $cridentials = $request->only('email', 'password');
            $token = Auth::attempt($cridentials);

            if(!$token){
                return response()->json([
                    'status'=>'error',
                    'message'=>'unauthorized'
                ], 401);
            }

            $user = Auth::user();
            return response()->json([
                'status'=> 'success',
                'user'=> $user,
                'authorisation'=> [
                    'token' => $token,
                    'type' => 'bearer'
                ]
            ]);
        }

        /* Register API */
        public function register(Request $request) {
            $validatedData = $request->validate([
                'name'=>'required|string|max:255',
                'dob' =>'required|date',
                'address' => 'required|string|max:255',
                'phone_number' =>'required|string|max:255',
                'role_id' =>'required|integer',
                'branch_id' =>'required|integer',
                'gender' => 'required|string|in:male,female',
                'password'=>'required|string|min:6',
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

            return response()->json([
                'status' => 'success',
                'message'=>'User Registered Successfully',
                'user'=>$user,
                'authorisation'=> [
                    // 'token' => $token,
                    'type' => 'bearer'
                ]
            ], 201);
        }

        /*User Detail API */
        public function userDetails() {
            return response()->json(auth()->user());
        }

        /**
         * Log the user out (Invalidate the token).
         *
         * @return \Illuminate\Http\JsonResponse
        */
        public function logout() {
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        }

        /**
         * Refresh a token.
         *
         * @return \Illuminate\Http\JsonResponse
        */
        public function refresh()
        {
            return $this->respondWithToken(auth()->refresh());
        }

        /**
         * Get the token array structure.
         *
         * @param  string $token
         *
         * @return \Illuminate\Http\JsonResponse
         */
        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }
    }
