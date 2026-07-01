<?php
namespace Database\Seeders;

use App\Models\{Category, Product};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void {
        $byName = fn(string $n) => Category::where('name',$n)->first();

        $items = [
            ['Sabuk Kulit Pria Classic',  'BLT-M-001', 'men',   249000, 25, ['Sabuk Pria']],
            ['Sabuk Anyam Pria Cokelat',  'BLT-M-002', 'men',   299000, 12, ['Sabuk Pria']],
            ['Dompet Bifold Pria Hitam',  'WLT-M-001', 'men',   329000,  4, ['Dompet Pria']],
            ['Dompet Trifold Pria Brown', 'WLT-M-002', 'men',   349000,  9, ['Dompet Pria']],
            ['Cufflinks Silver Round',    'CFL-M-001', 'men',   189000,  3, ['Cufflinks']],
            ['Cufflinks Gold Square',     'CFL-M-002', 'men',   219000,  0, ['Cufflinks']],
            ['Sabuk Wanita Slim Black',   'BLT-W-001', 'women', 219000, 18, ['Sabuk Wanita']],
            ['Sabuk Wanita Hoop Gold',    'BLT-W-002', 'women', 259000,  7, ['Sabuk Wanita']],
            ['Dompet Wanita Long Beige',  'WLT-W-001', 'women', 359000, 14, ['Dompet Wanita']],
            ['Dompet Card Holder Pink',   'WLT-W-002', 'women', 199000, 22, ['Dompet Wanita']],
            ['Syal Sutra Bunga',          'SCF-W-001', 'women', 289000,  6, ['Syal & Scarf']],
            ['Scarf Linen Cream',         'SCF-W-002', 'women', 229000,  2, ['Syal & Scarf']],
            ['Topi Baseball Hitam',       'CAP-U-001', 'unisex',179000, 30, ['Topi']],
            ['Bucket Hat Khaki',          'CAP-U-002', 'unisex',189000, 11, ['Topi']],
            ['Kacamata Acetate Tortoise', 'EYE-U-001', 'unisex',459000,  5, ['Kacamata']],
            ['Kacamata Metal Round',      'EYE-U-002', 'unisex',389000,  8, ['Kacamata']],
        ];

        foreach ($items as [$name,$sku,$gender,$price,$stock,$cats]) {
            $p = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name'        => $name,
                    'slug'        => Str::slug($name),
                    'description' => "Aksesoris pilihan: $name. Material berkualitas, jahitan rapi, cocok untuk dipakai sehari-hari.",
                    'gender'      => $gender,
                    'price'       => $price,
                    'stock'       => $stock,
                    'image'       => null,
                    'is_active'   => true,
                ]
            );
            $ids = collect($cats)->map(fn($n) => optional($byName($n))->id)->filter()->all();
            $p->categories()->sync($ids);
        }
    }
}
