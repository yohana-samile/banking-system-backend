<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Customer extends Model {
        use HasFactory;
        protected $fillable = [
            'account_id', 'user_id', 'account_validation'
        ];
        public function user(){
            return $this->belongsTo(User::class);
        }
        public function account(){
            return $this->belongsTo(Account::class);
        }
        public function loans(){
            return $this->hasMany(Loan::class);
        }
        public function customerAccounts()
        {
            return $this->hasMany(CustomerAccount::class);
        }
        public function transactions() {
            return $this->hasMany(Transaction::class);
        }
    }
