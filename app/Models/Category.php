<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','gender','description'];

    protected static function booted(): void {
        static::saving(function (Category $c) {
            if (empty($c->slug)) $c->slug = Str::slug($c->name);
        });
    }

    // MANY-TO-MANY
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class);
    }
}