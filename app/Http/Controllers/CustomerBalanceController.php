<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Actual_balance;
    use DB;

    class CustomerBalanceController extends Controller
    {
        public function index(){
            $actual_balance = Actual_balance::get();
            return response()->json($actual_balance);
        }

        public function store(Request $request){
            $actual_balance = Actual_balance::create($request->all());
            return response()->json([
                "message" => "account type created"
            ], 201);
        }

        public function show($id){
            $actual_balance = Actual_balance::find($id);
            if (!empty($actual_balance)) {
                return response()->json($actual_balance, 200);
            }
            else{
                return response()->json([
                    "message" => "account not found"
                ], 404);
            }
        }

        public function update(Request $request, $id){
            $actual_balance = Actual_balance::findOrFail($id);
            $actual_balance->update($request->all());
            // return response()->json(['success' => "updated"], 200);
            return response()->json($actual_balance, 200);
        }

        public function destroy($id){
            // you can't delete customer balance

            // $actual_balance = Actual_balance::findOrFail($id);
            // $actual_balance->delete();
            // // return response()->json(['success' => "deleted"], 204);
            // return response()->json($actual_balance, 204);
        }
        public function show_accounts($id){
            $user = DB::select("SELECT customers.id FROM `customers` WHERE user_id = ?", [$id]);
            $user = $user[0];
            $user_id = $user->id;
            $my_accounts = DB::select("SELECT customers.id, accounts.name as account_name FROM customers, accounts WHERE customers.account_id = accounts.id AND customers.id = '$user_id' ");
            return response()->json($my_accounts);
        }
    }
