<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN     = 'admin';
    public const ROLE_CASHIER   = 'cashier';
    public const ROLE_WAREHOUSE = 'warehouse';
    public const ROLE_CUSTOMER  = 'customer';

    protected $fillable = ['name','email','password','role','phone','address'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function isAdmin():     bool { return $this->role === self::ROLE_ADMIN; }
    public function isCashier():   bool { return $this->role === self::ROLE_CASHIER; }
    public function isWarehouse(): bool { return $this->role === self::ROLE_WAREHOUSE; }
    public function isCustomer():  bool { return $this->role === self::ROLE_CUSTOMER; }

    public function orders(): HasMany       { return $this->hasMany(Order::class); }
    public function handledOrders(): HasMany{ return $this->hasMany(Order::class, 'cashier_id'); }
}