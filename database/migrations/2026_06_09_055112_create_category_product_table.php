<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// MANY-TO-MANY pivot for products <-> categories
return new class extends Migration {
    public function up(): void {
        Schema::create('category_product', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->unique(['category_id','product_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('category_product'); }
};