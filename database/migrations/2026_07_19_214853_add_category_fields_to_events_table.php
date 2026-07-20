<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('location_id')->constrained()->nullOnDelete();
            $table->string('meeting_point')->nullable()->after('capacity');   // 실제 만남 장소 (승인자에게만 공개)
            $table->text('guide_note')->nullable()->after('meeting_point');   // 드레스코드/준비물 등 카테고리별 안내
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'meeting_point', 'guide_note']);
        });
    }
};