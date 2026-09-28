<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('payment_popup_settings', function(Blueprint $table){$table->id();$table->decimal('unlock_fee_amount',28,8)->default(0);$table->string('fee_currency',20)->default('BDT');$table->boolean('is_trx_required')->default(false);$table->boolean('is_proof_required')->default(false);$table->timestamps();}); } public function down(): void { Schema::dropIfExists('payment_popup_settings'); } };
