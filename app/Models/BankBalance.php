<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class BankBalance extends Model
    {
        use HasFactory;
        protected $fillable = ['balance', 'branch_id', 'status'];
        public function branch(){
            return $this->belongsTo(Branch::class);
        }
    }
