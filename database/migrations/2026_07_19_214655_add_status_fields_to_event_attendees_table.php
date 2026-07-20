<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            $table->string('approval_status')->default('pending')->after('status');
            $table->string('badge_no')->nullable()->after('table_no');
            $table->timestamp('checked_in_at')->nullable()->after('badge_no');
        });
    }

    public function down(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'badge_no', 'checked_in_at']);
        });
    }
};