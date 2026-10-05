<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedInteger('duration_days');
            $table->decimal('amount', 28, 8);
            $table->string('currency', 20)->default('USD');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('user_exchange_unlocks', function (Blueprint $table) {
            $table->foreignId('subscription_plan_id')->nullable()->constrained('payment_subscription_plans')->nullOnDelete();
            $table->unsignedInteger('subscription_duration_days')->nullable();
            $table->decimal('subscription_amount', 28, 8)->nullable();
            $table->string('subscription_currency', 20)->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'exchange_unlocked_until')) {
                $table->timestamp('exchange_unlocked_until')->nullable();
            }
        });

        if (DB::table('payment_subscription_plans')->count() === 0) {
            $now = now();
            DB::table('payment_subscription_plans')->insert([
                ['name' => '1 Day Subscription', 'duration_days' => 1, 'amount' => 50, 'currency' => 'USD', 'status' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => '2 Days Subscription', 'duration_days' => 2, 'amount' => 70, 'currency' => 'USD', 'status' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => '5 Days Subscription', 'duration_days' => 5, 'amount' => 100, 'currency' => 'USD', 'status' => true, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('user_exchange_unlocks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subscription_plan_id');
            $table->dropColumn(['subscription_duration_days', 'subscription_amount', 'subscription_currency']);
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'exchange_unlocked_until')) {
                $table->dropColumn('exchange_unlocked_until');
            }
        });

        Schema::dropIfExists('payment_subscription_plans');
    }
};
