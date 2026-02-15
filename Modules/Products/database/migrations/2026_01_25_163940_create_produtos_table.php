<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("produtos", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("nome");
            $table->string("marca");
            $table->string("modelo");
            $table->string("cor");
            $table->decimal("preco", 10, 2);
            $table->foreignUuid("categoria_produto_id")->constrained("categorias_produtos")->cascadeOnDelete();
            $table->bigInteger("created_at")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("produtos");
    }
};
