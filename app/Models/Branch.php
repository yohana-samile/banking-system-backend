<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Branch extends Model {
        use HasFactory;
        protected $fillable = [
            'name', 'address', 'branch_number', 'country', 'city', 'district', 'zip_code'
        ];
        public function employees(){
            return $this->hasMany(Employee::class);
        }
    }
