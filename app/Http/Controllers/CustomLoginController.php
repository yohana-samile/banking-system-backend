<?php
    namespace App\Http\Controllers\Auth;
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\Role;
    use Illuminate\Support\Facades\Auth;

    class CustomLoginController extends Controller {
        public function users(){
            $users = User::all();
            return response()->json($users);
        }
        public function roles(){
            $roles = Role::take(4)->orderBy('id','desc')->get()->where('name', '!=', 'is_customer');
            return response()->json($roles);
        }

        public function login(Request $request){
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json(['message' => 'Login successful'], 200);
            }
            else {
                return response()->json(['message' => 'Invalid email or password'], 401);
            }
        }
        public function logout(Request $request) {
            Auth::logout();
            return response()->json(200);
        }
    }
