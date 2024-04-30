<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Branch extends Model {
        use HasFactory;
        protected $fillable = [
            'branch_number', 'name', 'address', 'country', 'city', 'district', 'zip_code'
        ];
    }
