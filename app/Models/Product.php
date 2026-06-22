<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    public const LOW_STOCK_THRESHOLD = 5;

    protected $fillable = ['sku','name','slug','description','gender','price','stock','image','is_active'];
    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];

    protected static function booted(): void {
        static::saving(function (Product $p) {
            if (empty($p->slug)) $p->slug = Str::slug($p->name).'-'.Str::lower(Str::random(4));
        });
    }

    // MANY-TO-MANY
    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class);
    }

    public function stockMovements(): HasMany {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool   { return $this->stock > 0 && $this->stock < self::LOW_STOCK_THRESHOLD; }
    public function isOutOfStock(): bool { return $this->stock <= 0; }

    public function imageUrl(): string {
        return $this->image
            ? asset('storage/'.$this->image)
            : 'https://placehold.co/600x600/efefef/111?text='.urlencode($this->name);
    }
}