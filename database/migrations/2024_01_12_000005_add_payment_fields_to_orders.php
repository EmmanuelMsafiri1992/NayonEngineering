<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('notes');
            $table->string('payment_status')->default('pending')->after('payment_reference');
            $table->string('payment_method')->default('paystack')->after('payment_status');
            $table->string('currency', 3)->default('ZAR')->after('payment_method');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000)->after('currency');
            $table->string('locale', 5)->default('en')->after('exchange_rate');
            $table->timestamp('paid_at')->nullable()->after('locale');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_reference',
                'payment_status',
                'payment_method',
                'currency',
                'exchange_rate',
                'locale',
                'paid_at',
            ]);
        });
    }
};
