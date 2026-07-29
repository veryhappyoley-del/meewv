<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('method')->nullable()->after('provider'); // kakaopay, naverpay, tosspay, bank_transfer
            $table->string('depositor_name')->nullable()->after('method'); // 무통장입금 입금자명
            $table->boolean('cash_receipt_requested')->default(false)->after('depositor_name');
            $table->string('cash_receipt_type')->nullable()->after('cash_receipt_requested'); // personal, business
            $table->string('cash_receipt_number')->nullable()->after('cash_receipt_type'); // 휴대폰번호 또는 사업자번호
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['method', 'depositor_name', 'cash_receipt_requested', 'cash_receipt_type', 'cash_receipt_number']);
        });
    }
};
