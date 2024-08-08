<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enterprise_client_pivots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Enterprise\Enterprise::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Enterprise\EnterpriseClient::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['enterprise_id', 'enterprise_client_id'], 'enterprise_client_pivots_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('enterprise_clients', function (Blueprint $table) {
            $table->dropForeign(['enterprise_id']);
            $table->dropForeign(['enterprise_client_id']);
            $table->dropIfExists();
        });
    }
};
