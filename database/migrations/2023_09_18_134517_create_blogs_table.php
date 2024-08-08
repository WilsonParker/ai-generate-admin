<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Blog\BlogCategory::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Blog\BlogAuthor::class)->constrained()->cascadeOnDelete();
            $table->string('title', 512);
            $table->string('sub_title', 1024);
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['blog_category_id']);
            $table->dropForeign(['blog_author_id']);
            $table->dropIfExists();
        });
    }
};
