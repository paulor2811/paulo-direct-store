<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('preco');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('comment');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false)->after('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('is_visible');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_banned');
        });
    }
};
