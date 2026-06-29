<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            // Payment (Midtrans)
            $t->string('snap_token')->nullable()->after('notes');
            $t->string('payment_status')->default('unpaid')->after('snap_token');
            $t->string('payment_method')->nullable()->after('payment_status');
            $t->text('payment_details')->nullable()->after('payment_method');
            // Shipping
            $t->string('shipping_method')->nullable()->after('payment_details');
            $t->decimal('shipping_cost', 12, 2)->default(0)->after('shipping_method');
            $t->text('shipping_address')->nullable()->after('shipping_cost');
            $t->string('shipping_status')->nullable()->after('shipping_address');
            $t->string('tracking_number')->nullable()->after('shipping_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->dropColumn([
                'snap_token', 'payment_status', 'payment_method', 'payment_details',
                'shipping_method', 'shipping_cost', 'shipping_address',
                'shipping_status', 'tracking_number',
            ]);
        });
    }
};
