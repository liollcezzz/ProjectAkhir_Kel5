<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_movements', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->enum('type', ['restock','sale','adjustment']);
            $t->integer('quantity_change');   // + restock, - sale
            $t->integer('stock_after');
            $t->string('reference')->nullable(); // e.g. order code
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stock_movements'); }
};
