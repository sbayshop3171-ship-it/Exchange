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
        // The installer SQL dump already includes this legacy table. Keep the
        // migration safe for both fresh migration-only databases and installs
        // initialized from that baseline dump.
        if (Schema::hasTable('rate_alerts')) {
            return;
        }

        Schema::create('rate_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_currency_id');
            $table->unsignedBigInteger('to_currency_id');
            $table->decimal('target_rate', 28, 8)->default(0);
            $table->string('alert_email', 40)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_alerts');
    }
};
