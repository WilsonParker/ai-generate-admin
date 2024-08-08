<?php

use App\Models\User\User;
use App\Service\SellerPayout\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seller_payouts', function (Blueprint $table) {
            $table->id();
            $table->char('calculated_at', 7)->index()->default('0000-00')->comment('계산된 정산 날짜');
            $table->foreignIdFor(User::class)
                  ->constrained(new Expression(DB::connection('api')->getDatabaseName() . '.users'))
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->unsignedFloat('generated_revenue', 16, 12)->nullable(false)->default(0)->comment('제너레이트 수익');
            $table->unsignedFloat('payout_amount', 16, 12)->nullable(false)->default(0)->comment('지급 금액');
            $table->unsignedFloat('remaining_amount', 16, 12)->nullable(false)->default(0)->comment('남은 금액');
            $table->string('seller_payout_status_code', 32)->nullable(false)->default(Status::Wait->value)->comment(
                'seller payout status code'
            );
            $table->foreign('seller_payout_status_code')->references('code')->on('seller_payout_status')
                  ->onUpdate('cascade');
            $table->string('event_id', 256)->nullable(true)->comment('stripe event id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_payouts', function (Blueprint $table) {
            $table->dropForeign(['seller_payout_status_code']);
            $table->dropForeign(['user_id']);
            $table->dropIfExists();
        });
    }
};
