<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("CREATE TABLE IF NOT EXISTS orders_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT NOT NULL UNIQUE,
                user_id INTEGER NULL,
                cashier_id INTEGER NULL,
                channel TEXT NOT NULL DEFAULT 'online' CHECK (channel IN ('online','pos')),
                status TEXT NOT NULL DEFAULT 'pending' CHECK (status IN ('pending','confirmed','paid','shipped','completed','cancelled')),
                subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
                tax DECIMAL(12,2) NOT NULL DEFAULT 0,
                total DECIMAL(12,2) NOT NULL DEFAULT 0,
                amount_paid DECIMAL(12,2) NOT NULL DEFAULT 0,
                change_due DECIMAL(12,2) NOT NULL DEFAULT 0,
                customer_name TEXT NULL,
                customer_phone TEXT NULL,
                notes TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (cashier_id) REFERENCES users(id) ON DELETE SET NULL
            )");

            DB::statement("INSERT INTO orders_new SELECT * FROM orders");
            DB::statement("DROP TABLE orders");
            DB::statement("ALTER TABLE orders_new RENAME TO orders");
        } else {
            Schema::table('orders', function ($table) {
                $table->enum('status', ['pending','confirmed','paid','shipped','completed','cancelled'])->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("UPDATE orders SET status = 'pending' WHERE status = 'confirmed'");

            DB::statement("CREATE TABLE IF NOT EXISTS orders_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT NOT NULL UNIQUE,
                user_id INTEGER NULL,
                cashier_id INTEGER NULL,
                channel TEXT NOT NULL DEFAULT 'online' CHECK (channel IN ('online','pos')),
                status TEXT NOT NULL DEFAULT 'pending' CHECK (status IN ('pending','paid','shipped','completed','cancelled')),
                subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
                tax DECIMAL(12,2) NOT NULL DEFAULT 0,
                total DECIMAL(12,2) NOT NULL DEFAULT 0,
                amount_paid DECIMAL(12,2) NOT NULL DEFAULT 0,
                change_due DECIMAL(12,2) NOT NULL DEFAULT 0,
                customer_name TEXT NULL,
                customer_phone TEXT NULL,
                notes TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (cashier_id) REFERENCES users(id) ON DELETE SET NULL
            )");

            DB::statement("INSERT INTO orders_old SELECT * FROM orders");
            DB::statement("DROP TABLE orders");
            DB::statement("ALTER TABLE orders_old RENAME TO orders");
        } else {
            Schema::table('orders', function ($table) {
                $table->enum('status', ['pending','paid','shipped','completed','cancelled'])->default('pending')->change();
            });
        }
    }
};
