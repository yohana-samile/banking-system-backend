<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CustomerQuery extends Model {
        use HasFactory;
        protected $fillable = [
            'user_id', 'sms_sent', 'replay', 'status'
        ];
        public function user(){
            return $this->belongsTo(User::class);
        }
    }
