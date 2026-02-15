<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("fotos_produtos", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("produto_id")->constrained("produtos")->cascadeOnDelete();
            $table->string("caminho_imagem");
            $table->bigInteger("created_at")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("fotos_produtos");
    }
};
