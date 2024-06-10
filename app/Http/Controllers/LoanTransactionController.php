<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Loan;
    use App\Models\User;
    use App\Models\Customer;
    use DB;
    use Illuminate\Support\Facades\Validator;
    use Carbon\Carbon;

    class LoanTransactionController extends Controller {
        public function index(){
            $loans = DB::select("SELECT users.name, users.email, users.address, users.dob, users.gender, users.phone_number, loans.amount, loans.interest_rate, loans.interest_rate, loans.term, loans.start_date, loans.status, loans.created_at, loans.calculated_interest, loans.id FROM
                users, customers, loans WHERE
                customers.user_id = users.id AND
                loans.customer_id = customers.id
            ");
            return response()->json($loans);
        }

        public function storeLoan(Request $request){
            $validatedData = $request->validate([
                'customer_id' =>'required',
                'amount' => 'required|numeric|min:0',
                'term' => 'required|boolean',
            ]);

            $customer = DB::table('customers')->where('user_id', $validatedData['customer_id'])->first();

            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }
            try{

                $start_date = Carbon::now()->format('Y-m-d');
                if ($validatedData['amount'] < 500000) {
                    $interest_rate = 0.05;
                    // $calculated_interest_rate = $validatedData['amount'] * $interest_rate % 100;
                }
                if ($validatedData['amount'] > 500000 && $validatedData['amount'] <= 1000000) {
                    $interest_rate = 0.07;
                }
                elseif ($validatedData['amount'] > 1000000){
                    $interest_rate = 0.10;
                }
                $calculated_interest = $validatedData['amount'] * $interest_rate;
                $user = Loan::create([
                    'customer_id' => $customer->id,
                    'amount' => $validatedData['amount'],
                    'interest_rate' => $interest_rate * 100,
                    'calculated_interest' => $calculated_interest,
                    'term' => $validatedData['term'],
                    'start_date' => $start_date,
                    'status' => "pending"
                ]);
                return response()->json(["success" => "Loan created"], 201);
            }
            catch(\Exception $e){
                return response()->json(['error' => 'Loan Request Failed, please try again', 'details' => $e->getMessage()], 500);
            }
        }

        public function show(Request $request, $id) {
            $loan_id = Loan::findOrFail($id);
            $loan = DB::select("SELECT users.name, users.email, users.address, users.dob, users.gender, users.phone_number, loans.amount, loans.interest_rate, loans.interest_rate, loans.term, loans.start_date, loans.status, loans.created_at, loans.calculated_interest, loans.id FROM
                users, customers, loans WHERE
                customers.user_id = users.id AND
                loans.customer_id = customers.id and loans.id = '$loan_id'
            ");
            if (!empty($loan)) {
                return response()->json($loan);
            }
            else{
                return response()->json(["error" => "no data found"]);
            }
        }

        public function updateLoan(Request $request, $id){
            $loan = Loan::findOrFail($id);
            $validatedData = $request->validate([
                'customer_id' =>'nullable',
                'amount' =>'nullable|numeric',
                'term' =>'nullable',
                'start_date' =>'nullable|date'
            ]);
            $interest_rate = null;
            $calculated_interest = null;
            if (isset($validatedData['amount'])) {
                if ($validatedData['amount'] < 500000) {
                    $interest_rate = 0.05;
                }
                elseif ($validatedData['amount'] > 500000 && $validatedData['amount'] <= 1000000) {
                    $interest_rate = 0.07;
                }
                elseif ($validatedData['amount'] > 1000000) {
                    $interest_rate = 0.10;
                }
                // Calculate the interest amount
                $calculated_interest = $validatedData['amount'] * $interest_rate;
            }

            // Prepare the data to update, filtering out null values
            $dataToUpdate = array_filter([
                'customer_id' => $validatedData['customer_id'] ?? $loan->customer_id,
                'amount' => $validatedData['amount'] ?? $loan->amount,
                'term' => $validatedData['term'] ?? $loan->term,
                'start_date' => $validatedData['start_date'] ?? $loan->start_date,
                'status' => "borrow",
                'interest_rate' => isset($interest_rate) ? $interest_rate * 100 : $loan->interest_rate,
                'calculated_interest' => $calculated_interest ?? $loan->calculated_interest
            ], function ($value) {
                return $value !== null;
            });

            $loan->update($dataToUpdate);
            return response()->json($loan, 200);
        }


        // return loan
        public function returnLoan(Request $request, $id){
            $validatedData = $request->validate([
                'status' => 'required|string',
            ]);

            try {
                $loan = Loan::findOrFail($id);
                $loan->status = $validatedData['status'];
                $loan->save();

                return response()->json(['message' => 'Loan Status Updated Successfully'], 200);
            }
            catch (\Exception $e) {
                return response()->json(['error' => 'Failed to Update Loan Status, please try again', 'details' => $e->getMessage()], 500);
            }
        }

        // customerReturnLoan
        public function customerReturnLoan(Request $request, $id){
            $validatedData = $request->validate([
                'status' => 'required|string',
            ]);

            try {
                $loan = Loan::findOrFail($id);
                $loan->status = $validatedData['status'];
                $loan->save();

                return response()->json(['message' => 'Loan Status Updated Successfully'], 200);
            }
            catch (\Exception $e) {
                return response()->json(['error' => 'Failed to Update Loan Status, please try again', 'details' => $e->getMessage()], 500);
            }
        }
        public function destroy(Request $request, $id){
            $loan = Loan::findOrFail($id);
            $loan->delete($loan);
            return response()->json($loan, 200);
        }
    }
