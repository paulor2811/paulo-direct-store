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
        Schema::create('media_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Polymorphic relationship to any model (User, Produto, etc)
            $table->string('model_type');
            $table->string('model_id');
            $table->index(['model_type', 'model_id']);
            
            // File metadata
            $table->string('file_type'); // 'profile', 'product_image', etc
            $table->string('disk')->default('s3');
            $table->string('path'); // Full S3 path: profiles/123/profile.jpg
            $table->string('filename'); // Original filename
            $table->string('mime_type');
            $table->unsignedBigInteger('size'); // bytes
            
            // Optional metadata (URLs, thumbnails, processing status, etc)
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
