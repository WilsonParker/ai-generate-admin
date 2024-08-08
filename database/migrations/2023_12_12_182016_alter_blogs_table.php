<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedTinyInteger('order')->default(0)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
