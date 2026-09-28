<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_popup_gateways', function (Blueprint $table) {
            $table->boolean('is_trx_required')->default(false)->after('qr_code_image');
            $table->boolean('is_proof_required')->default(false)->after('is_trx_required');
        });
    }

    public function down(): void
    {
        Schema::table('payment_popup_gateways', function (Blueprint $table) {
            $table->dropColumn(['is_trx_required', 'is_proof_required']);
        });
    }
};
