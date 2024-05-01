<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Customer extends Model {
        use HasFactory;
        protected $fillable = [
            'first_name', 'middle_name', 'surname', 'dob', 'gender', 'account_id', 'user_id'
        ];
        public function account(){
            return $this->belongsTo(Account::class);
        }
        public function user(){
            return $this->belongsTo(User::class);
        }
        public function loans(){
            return $this->hasMany(Loan::class);
        }
    }
