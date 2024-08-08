<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_tag_pivots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Blog\Blog::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Blog\BlogTag::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['blog_id', 'blog_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_tag_pivots', function (Blueprint $table) {
//            $table->dropForeignIdFor(\App\Models\Blog\Blog::class);
//            $table->dropForeignIdFor(\App\Models\Blog\BlogTag::class);
//            $table->dropUnique(['blog_id', 'blog_tag_id']);
            $table->dropIfExists();
        });
    }
};
