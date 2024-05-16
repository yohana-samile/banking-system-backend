<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Account extends Model {
        use HasFactory;
        protected $fillable = [
            'name', 'account_for', 'account_type_id'
        ];
        public function customers(){
            return $this->hasMany(Customer::class);
        }
        public function accountType(){
            return $this->belongsTo(AccountType::class);
        }
    }
