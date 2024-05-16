<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\AccountType;

    class AccountTypeController extends Controller {
        public function index(){
            $accountType = AccountType::get();
            return response()->json($accountType);
        }

        public function store(Request $request){
            $accountType = AccountType::create($request->all());
            return response()->json([
                "message" => "account type created"
            ], 201);
        }

        public function show($id){
            $accountType = AccountType::find($id);
            if (!empty($accountType)) {
                return response()->json($accountType, 200);
            }
            else{
                return response()->json([
                    "message" => "account not found"
                ], 404);
            }
        }

        public function update(Request $request, $id){
            $accountType = AccountType::findOrFail($id);
            $accountType->update($request->all());
            // return response()->json(['success' => "updated"], 200);
            return response()->json($accountType, 200);
        }

        public function destroy($id){
            $accountType = AccountType::findOrFail($id);
            $accountType->delete();
            // return response()->json(['success' => "deleted"], 204);
            return response()->json($accountType, 204);
        }
    }
