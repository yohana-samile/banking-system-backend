<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Employee extends Model {
        use HasFactory;
        protected $fillable = [
           'branch_id', 'dob', 'gender', 'user_id'
        ];
        public function user(){
            return $this->belongsTo(User::class);
        }
        public function branch(){
            return $this->belongsTo(Branch::class);
        }
    }
