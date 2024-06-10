<?php
    namespace App\Http\Controllers;
    use App\Models\CustomerQuery;
    use Illuminate\Http\Request;
    use DB;

    class CustomerSmSQuery extends Controller {
        public function index(){
            // $queries = CustomerQuery::all();
            $queries = DB::select("SELECT users.name as sender_name, customer_queries.sms_sent, customer_queries.replay, customer_queries.status FROM users , customer_queries WHERE customer_queries.user_id = users.id");
            return response()->json($queries);
        }

        public function storeQuery(Request $request){
            $store = CustomerQuery::create($request->all());
            return response()->json(["success" => "Query submitted"], 201);
        }

        public function updateQueryFeedback(Request $request, $id){
            $query = CustomerQuery::findOrFail($id);
            $query->update($request->all());
            return response()->json($query, 200);
        }

        public function destroyQuery(Request $request, $id){
            $query =CustomerQuery::findOrFail($id);
            $query->delete($query);
            return response()->json($query, 200);
        }
    }
