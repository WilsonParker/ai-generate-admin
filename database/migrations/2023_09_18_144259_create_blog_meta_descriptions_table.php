<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blog_meta_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Blog\Blog::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('description', 256);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('blog_meta_descriptions', function (Blueprint $table) {
            $table->dropForeign(['blog_id']);
            $table->dropIfExists();
        });
    }
};
