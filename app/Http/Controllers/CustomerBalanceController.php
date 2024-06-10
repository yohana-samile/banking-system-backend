<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Actual_balance;
    use App\Models\CustomerTranscation;
    use App\Models\TransactionHistory;
    use Illuminate\Support\Facades\Validator;
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

        // deposit
        public function deposit(Request $request){
            $validatedData = $request->validate([
                'transaction_type' =>'required|string',
                'amount' =>'required|numeric',
                'description' =>'nullable|string',
                'customer_account_id' =>'required|integer',
            ]);
            $customer_account_id = $validatedData['customer_account_id'];
            $data = DB::table('customer_transcations')->where('customer_account_id', $customer_account_id)->first();
            if (empty($data)) {
                $transaction = CustomerTranscation::create([
                    'transaction_type' => $validatedData['transaction_type'],
                    'amount' => $validatedData['amount'],
                    'description' => $validatedData['description'],
                    'customer_account_id' => $validatedData['customer_account_id'],
                ]);
                if ($transaction) {
                    $insert = TransactionHistory::create([
                        'transaction_type' => $validatedData['transaction_type'],
                        'amount' => $validatedData['amount'],
                        'description' => $validatedData['description'],
                        'customer_account_id' => $validatedData['customer_account_id'],
                    ]);
                }
                return response()->json(["message" => "Deposit successfully"], 201);
            }
            else{
                $id = $data->id;
                DB::beginTransaction();
                try {
                    // Update the balance
                    $newBalance = $data->amount + $validatedData['amount'];
                    DB::table('customer_transcations')->where('id', $id)->update(['amount' => $newBalance]);

                    $transaction = TransactionHistory::create([
                        'transaction_type' => $validatedData['transaction_type'],
                        'amount' => $validatedData['amount'],
                        'description' => $validatedData['description'],
                        'customer_account_id' => $validatedData['customer_account_id'],
                    ]);

                    DB::commit();
                    return response()->json(["message" => "Deposit successfully"], 201);

                }
                catch (\Exception $e) {
                    // Rollback the transaction in case of error
                    DB::rollBack();
                    return response()->json(["error" => "Transaction failed, please try again", "details" => $e->getMessage()], 500);
                }
            }
        }

        //withdraw
        public function withdraw(Request $request){
            $validatedData = $request->validate([
                'transaction_type' =>'required|string',
                'amount' =>'required|numeric',
                'description' =>'nullable|string',
                'customer_account_id' =>'required|integer',
            ]);
            $customer_account_id = $validatedData['customer_account_id'];
            $data = DB::table('customer_transcations')->where('customer_account_id', $customer_account_id)->first();
            $id = $data->id;
            if (!$data) {
                return response()->json(["error" => "No Balance found"], 404);
            }
            if ($validatedData['amount'] > $data->amount) {
                return response()->json(["error" => "You don't have enough balance"]);
            }

            DB::beginTransaction();
            try {
                // Update the balance
                $newBalance = $data->amount - $validatedData['amount'];
                DB::table('customer_transcations')->where('id', $id)->update(['amount' => $newBalance]);

                $transaction = TransactionHistory::create([
                    'transaction_type' => $validatedData['transaction_type'],
                    'amount' => $validatedData['amount'],
                    'description' => $validatedData['description'],
                    'customer_account_id' => $validatedData['customer_account_id'],
                ]);

                DB::commit();
                return response()->json(["message" => "Withdraw successfully"], 201);

            }
            catch (\Exception $e) {
                // Rollback the transaction in case of error
                DB::rollBack();
                return response()->json(["error" => "Transaction failed, please try again", "details" => $e->getMessage()], 500);
            }
        }

        // transfer
        public function transfer(Request $request){
            $validatedData = $request->validate([
                'transaction_type' =>'required|string',
                'amount' =>'required|numeric',
                'description' =>'nullable|string',
                'account_number' =>'nullable|string',
                'receiver' =>'nullable|string',
                'customer_account_id' =>'required|integer',
            ]);

            if ($validatedData['receiver'] != null) {
                // trnfer to another person
                $id = User::findOrFail($validatedData['receiver']);
                if (!empty($id)) {
                    $get_customer_id = DB::table('customers')->where('user_id', $id)->first();
                    $customer_id = $get_customer_id->id;
                    $data = DB::table('customer_transcations')->where('customer_account_id', $customer_id)->first();
                    if (empty($data)) {
                        $transaction = CustomerTranscation::create([
                            'transaction_type' => $validatedData['transaction_type'],
                            'amount' => $validatedData['amount'],
                            'description' => $validatedData['description'],
                            'customer_account_id' => $customer_id,
                        ]);
                        if ($transaction) {
                            $insert = TransactionHistory::create([
                                'transaction_type' => $validatedData['transaction_type'],
                                'amount' => $validatedData['amount'],
                                'description' => "amount deposited for you",
                                'customer_account_id' => $customer_id,
                            ]);
                        }
                    }
                    else{
                        $transcationId = $data->id;
                        DB::beginTransaction();
                        try {
                            // Update the balance
                            $newBalance = $data->amount + $validatedData['amount'];
                            DB::table('customer_transcations')->where('id', $transcationId)->update(['amount' => $newBalance]);

                            $transaction = TransactionHistory::create([
                                'transaction_type' => $validatedData['transaction_type'],
                                'amount' => $validatedData['amount'],
                                'description' => $validatedData['description'],
                                'customer_account_id' => $customer_id,
                            ]);

                            DB::commit();
                            return response()->json(["message" => "Deposit successfully"], 201);

                        }
                        catch (\Exception $e) {
                            // Rollback the transaction in case of error
                            DB::rollBack();
                            return response()->json(["error" => "Transaction failed, please try again", "details" => $e->getMessage()], 500);
                        }
                    }
                }

                DB::beginTransaction();
                try {
                    // Update the balance
                    $newBalance = $data->amount - $validatedData['amount'];
                    DB::table('customer_transcations')->where('id', $id)->update(['amount' => $newBalance]);

                    $transaction = TransactionHistory::create([
                        'transaction_type' => $validatedData['transaction_type'],
                        'amount' => $validatedData['amount'],
                        'description' => $validatedData['description'],
                        'customer_account_id' => $validatedData['customer_account_id'],
                    ]);

                    DB::commit();
                    return response()->json(["message" => "Transfer successfully"], 201);

                }
                catch (\Exception $e) {
                    // Rollback the transaction in case of error
                    DB::rollBack();
                    return response()->json(["error" => "Transaction failed, please try again", "details" => $e->getMessage()], 500);
                }
            }
            else if($validatedData['account_number'] != null){
                $customer_account_id = $validatedData['customer_account_id'];
                $data = DB::table('customer_transcations')->where('customer_account_id', $customer_account_id)->first();
                if (empty($data)) {
                    $transaction = CustomerTranscation::create([
                        'transaction_type' => $validatedData['transaction_type'],
                        'amount' => $validatedData['amount'],
                        'description' => $validatedData['description'],
                        'customer_account_id' => $validatedData['customer_account_id'],
                    ]);
                    if ($transaction) {
                        $insert = TransactionHistory::create([
                            'transaction_type' => $validatedData['transaction_type'],
                            'amount' => $validatedData['amount'],
                            'description' => $validatedData['description'],
                            'customer_account_id' => $validatedData['customer_account_id'],
                        ]);
                    }
                    return response()->json(["message" => "Deposit successfully"], 201);
                }
                else{
                    $dataId = $data->id;
                    DB::beginTransaction();
                    try {
                        // Update the balance
                        $newBalance = $data->amount + $validatedData['amount'];
                        DB::table('customer_transcations')->where('id', $dataId)->update(['amount' => $newBalance]);

                        $transaction = TransactionHistory::create([
                            'transaction_type' => $validatedData['transaction_type'],
                            'amount' => $validatedData['amount'],
                            'description' => $validatedData['description'],
                            'customer_account_id' => $validatedData['customer_account_id'],
                        ]);

                        DB::commit();
                        return response()->json(["message" => "Deposit successfully"], 201);

                    }
                    catch (\Exception $e) {
                        // Rollback the transaction in case of error
                        DB::rollBack();
                        return response()->json(["error" => "Transaction failed, please try again", "details" => $e->getMessage()], 500);
                    }
                }
            }
            if ($validatedData['amount'] > $data->amount) {
                return response()->json(["error" => "You don't have enough balance"]);
            }
        }
    }
