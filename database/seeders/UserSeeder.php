<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void {
        $defs = [
            ['admin',    'Admin Utama',  'admin@aksesoria.test'],
            ['cashier',  'Kasir Toko',   'kasir@aksesoria.test'],
            ['warehouse','Staff Gudang', 'gudang@aksesoria.test'],
            ['customer', 'Pelanggan',    'customer@aksesoria.test'],
        ];
        foreach ($defs as [$role, $name, $email]) {
            User::updateOrCreate(
                ['email' => $email],
                ['name'=>$name,'role'=>$role,'password'=>'password']
            );
        }
    }
}
