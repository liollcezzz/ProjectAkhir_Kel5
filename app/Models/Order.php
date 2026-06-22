<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','user_id','cashier_id','channel','status',
        'subtotal','tax','total','amount_paid','change_due',
        'customer_name','customer_phone','notes',
    ];
    protected $casts = [
        'subtotal'=>'decimal:2','tax'=>'decimal:2','total'=>'decimal:2',
        'amount_paid'=>'decimal:2','change_due'=>'decimal:2',
    ];

    // ONE-TO-MANY
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function customer(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function cashier(): BelongsTo  { return $this->belongsTo(User::class, 'cashier_id'); }

    public static function generateCode(string $channel = 'online'): string {
        $prefix = $channel === 'pos' ? 'POS' : 'ORD';
        return sprintf('%s-%s-%04d', $prefix, now()->format('Ymd'), random_int(1, 9999));
    }
}