<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CustomerTranscation extends Model {
        use HasFactory;
        protected $fillable = [
            'transaction_type', 'amount', 'customer_account_id', 'description'
            // 'transaction_id', 'amount_deposit', 'amount_withdraw', 'actual_balance_id', 'account_id', 'user_id'
        ];
        public function customerAccount(){
            return $this->belongsTo(CustomerAccount::class);
        }
    }
