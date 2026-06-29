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
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        $colors = [
            'men'    => ['from' => '#1a1f2e', 'to' => '#2d3a5c'],
            'women'  => ['from' => '#3d2c3b', 'to' => '#6b4c5a'],
            'unisex' => ['from' => '#2c3e3a', 'to' => '#4a6b63'],
        ];
        $palette = $colors[$this->gender ?? 'unisex'] ?? $colors['unisex'];
        $initial = strtoupper(substr($this->name, 0, 1));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
  <defs>
    <linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$palette['from']}"/>
      <stop offset="100%" style="stop-color:{$palette['to']}"/>
    </linearGradient>
  </defs>
  <rect width="600" height="600" fill="url(#g)"/>
  <text x="300" y="300" text-anchor="middle" dominant-baseline="central" font-family="Georgia,serif" font-size="180" font-weight="700" fill="rgba(255,255,255,0.12)" letter-spacing="4">{$initial}</text>
  <text x="300" y="410" text-anchor="middle" dominant-baseline="central" font-family="Inter,sans-serif" font-size="16" font-weight="300" fill="rgba(255,255,255,0.35)" letter-spacing="6">{$this->label()}</text>
</svg>
SVG;

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function label(): string {
        return match($this->gender) {
            'men'    => 'MEN',
            'women'  => 'WOMEN',
            'unisex' => 'UNISEX',
            default  => 'ACCESSORY',
        };
    }
}
