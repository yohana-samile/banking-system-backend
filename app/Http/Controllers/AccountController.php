<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Account;

    class AccountController extends Controller {
        public function index(){
            $accounts = Account::all();
            return response()->json($accounts);
            // return view("accounts/index", compact('accounts'));
        }

        public function store(Request $request){
            $acoount = Account::create($request->all());
            return response()->json([
                "message" => "account created"
            ], 201);
        }

        public function show($id){
            $account = Account::find($id);
            if (!empty($account)) {
                return response()->json($account, 200);
            }
            else{
                return response()->json([
                    "message" => "account not found"
                ], 404);
            }
        }

        public function update(Request $request, $id){
            $account = Account::findOrFail($id);
            $account->update($request->all());
            // return response()->json(['success' => "updated"], 200);
            return response()->json($account, 200);
        }

        public function destroy($id){
            $account = Account::findOrFail($id);
            $account->delete();
            // return response()->json(['success' => "deleted"], 204);
            return response()->json($account, 204);
        }
    }
