<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('payment_popup_gateways', function (Blueprint $table) {
            $table->decimal('unlock_fee_amount', 28, 8)->default(0)->after('is_proof_required');
            $table->string('fee_currency', 20)->nullable()->after('unlock_fee_amount');
        });
    }
    public function down(): void {
        Schema::table('payment_popup_gateways', fn (Blueprint $table) => $table->dropColumn(['unlock_fee_amount', 'fee_currency']));
    }
};
