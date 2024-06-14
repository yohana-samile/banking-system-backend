<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Transaction;
    use DB;

    class TransactionController extends Controller {
        public function index(){
            $transactions = DB::select("SELECT users.name AS customer_name, customer_accounts.account_number, transaction_histories.transaction_type as type, transaction_histories.amount, transaction_histories.description, transaction_histories.created_at as transaction_date FROM
                users, customers, customer_accounts, transaction_histories WHERE
                customers.user_id= users.id AND
                customer_accounts.customer_id = customers.id AND
                transaction_histories.customer_account_id = customer_accounts.id
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
