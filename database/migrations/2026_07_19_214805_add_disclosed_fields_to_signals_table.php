<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signals', function (Blueprint $table) {
            $table->json('receiver_disclosed_fields')->nullable()->after('status');
            // 예: ["phone", "instagram_handle"] 이런 식으로 저장
        });
    }

    public function down(): void
    {
        Schema::table('signals', function (Blueprint $table) {
            $table->dropColumn('receiver_disclosed_fields');
        });
    }
};