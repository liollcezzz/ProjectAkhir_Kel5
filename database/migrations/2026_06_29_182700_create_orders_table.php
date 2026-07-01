<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();                       
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); 
            $t->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $t->enum('channel', ['online','pos'])->default('online');
            $t->enum('status', ['pending','paid','shipped','completed','cancelled'])->default('pending');
            $t->decimal('subtotal', 12, 2)->default(0);
            $t->decimal('tax', 12, 2)->default(0);
            $t->decimal('total', 12, 2)->default(0);
            $t->decimal('amount_paid', 12, 2)->default(0);
            $t->decimal('change_due', 12, 2)->default(0);
            $t->string('customer_name')->nullable();
            $t->string('customer_phone')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
