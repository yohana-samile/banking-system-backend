<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Actual_balance extends Model {
        use HasFactory;
        protected $fillable = [
            'balance', 'customer_account_id'
        ];
        public function customerAccount(){
            return $this->belongsTo(CustomerAccount::class);
        }
    }
