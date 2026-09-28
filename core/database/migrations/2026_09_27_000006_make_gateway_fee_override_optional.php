<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration { public function up(): void { DB::table('payment_popup_gateways')->where('unlock_fee_amount', 0)->update(['unlock_fee_amount'=>null,'fee_currency'=>null]); Schema::table('payment_popup_gateways', function(Blueprint $table){$table->decimal('unlock_fee_amount',28,8)->nullable()->default(null)->change();$table->string('fee_currency',20)->nullable()->default(null)->change();}); } public function down(): void {} };
