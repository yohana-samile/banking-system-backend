<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Transaction;
    use DB;
    
    class TransactionController extends Controller {
        public function index(){
            $transactions = DB::select("SELECT users.name AS customer_name, customers.id, transactions.type, transactions.amount, transactions.transaction_date FROM
                users, customers, transactions WHERE
                customers.user_id = users.id AND
                transactions.customer_id = customers.id
            ");
            return response()->json($transactions);
        }

        public function deposit(Request $request, $customerId)
        {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);

            $transaction = Transaction::create([
                'customer_id' => $customerId,
                'type' => 'deposit',
                'amount' => $request->amount,
            ]);

            return response()->json(['message' => 'Deposit successful', 'transaction' => $transaction], 201);
        }

        public function withdraw(Request $request, $customerId) {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);

            $customer = Customer::findOrFail($customerId);
            $totalBalance = $customer->transactions()->where('type', 'deposit')->sum('amount') - $customer->transactions()->where('type', 'withdrawal')->sum('amount');

            if ($request->amount > $totalBalance) {
                return response()->json(['message' => 'Insufficient balance'], 400);
            }

            $transaction = Transaction::create([
                'customer_id' => $customerId,
                'type' => 'withdrawal',
                'amount' => $request->amount,
            ]);

            return response()->json(['message' => 'Withdrawal successful', 'transaction' => $transaction], 201);
        }

        public function getTransactions($customerId)
        {
            $customer = Customer::findOrFail($customerId);
            $transactions = $customer->transactions;

            return response()->json($transactions, 200);
        }
    }
