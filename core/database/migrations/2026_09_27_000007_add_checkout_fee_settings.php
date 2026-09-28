<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::table('payment_popup_settings', function(Blueprint $table){$table->boolean('vat_enabled')->default(false);$table->decimal('vat_rate',8,3)->default(0);$table->boolean('fixed_fee_enabled')->default(false);$table->decimal('fixed_fee_amount',28,8)->default(0);}); } public function down(): void { Schema::table('payment_popup_settings', fn(Blueprint $table)=>$table->dropColumn(['vat_enabled','vat_rate','fixed_fee_enabled','fixed_fee_amount'])); } };
