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
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->string('subject', 256)->nullable(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('test_sent')->default(false);
            $table->string('reply_to', 64)->nullable();
            $table->string('to_field', 64)->nullable();
            $table->string('tag', 64)->nullable();
            $table->text('html_content')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_templates');
    }
};
