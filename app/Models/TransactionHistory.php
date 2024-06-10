<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class TransactionHistory extends Model {
        use HasFactory;
        protected $fillable = [
            'transaction_type', 'amount', 'customer_account_id', 'description'
        ];
        public function customerAccount(){
            return $this->belongsTo(CustomerAccount::class);
        }
    }

