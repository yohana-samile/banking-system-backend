<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CustomerAccount extends Model {
        use HasFactory;
        protected $fillable = [
            'customer_id', 'account_number'
        ];
        public function customer(){
            return $this->belongsTo(Customer::class);
        }
        public function customerTranscation(){
            return $this->hasMany(CustomerTranscation::class, 'account_id');
        }
        public function actual_balance(){
            return $this->hasOne(Actual_balance::class);
        }
    }
