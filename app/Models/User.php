<?php
    namespace App\Models;
    // use Illuminate\Contracts\Auth\MustVerifyEmail;
    use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    // use Laravel\Sanctum\HasApiTokens;

    class User extends Authenticatable implements JWTSubject {
        use Notifiable;
        // use HasFactory, Notifiable; This work for passport i will try in another project inshaallah

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
        */

        protected $fillable = [
            'name', 'email', 'address', 'phone_number', 'role_id', 'dob', 'gender', 'password'
        ];

        /**
         * The attributes that should be hidden for serialization.
         *
         * @var array<int, string>
        */
        protected $hidden = [
            'password',
            'remember_token',
        ];

        /**
         * The attributes that should be cast.
         *
         * @var array<string, string>
         */
        protected $casts = [
            'email_verified_at' => 'datetime', 'password' => 'hashed',
        ];


        /**
         * Get the identifier that will be stored in the subject claim of the JWT.
         *
         * @return mixed
        */
        public function getJWTIdentifier() {
            return $this->getKey();
        }

        /**
         * Return a key value array, containing any custom claims to be added to the JWT.
         *
         * @return array
        */
        public function getJWTCustomClaims() {
            return [];
        }

        public function customer(){
            return $this->hasOne(Customer::class);
        }
        public function employee(){
            return $this->hasOne(Employee::class);
        }
        public function role(){
            return $this->belongsTo(Role::class);
        }
    }
