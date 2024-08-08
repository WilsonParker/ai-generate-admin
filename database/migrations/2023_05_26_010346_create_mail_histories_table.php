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
        Schema::create('mail_histories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->string('subject', 128)->nullable(false);
            $table->text('htmlContent')->nullable();

            $table->foreignIdFor(\App\Service\Mail\Model\Template::class)
                  ->constrained('mail_templates')
                  ->onUpdate('cascade');
            $table->foreignIdFor(\App\Service\Mail\Model\Sender::class)
                ->constrained('mail_senders')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_histories');
    }
};
