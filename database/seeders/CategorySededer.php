<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void {
        $data = [
            ['Sabuk Pria',     'men',    "Men's belts"],
            ['Dompet Pria',    'men',    "Men's wallets"],
            ['Cufflinks',      'men',    "Cufflinks & tie clips"],
            ['Sabuk Wanita',   'women',  "Women's belts"],
            ['Dompet Wanita',  'women',  "Women's wallets"],
            ['Syal & Scarf',   'women',  "Scarves"],
            ['Topi',           'unisex', "Hats & caps"],
            ['Kacamata',       'unisex', "Eyewear"],
        ];
        foreach ($data as [$name,$gender,$desc]) {
            Category::updateOrCreate(['name'=>$name],['gender'=>$gender,'description'=>$desc]);
        }
    }
}