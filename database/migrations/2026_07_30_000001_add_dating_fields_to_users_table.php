<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('height')->nullable()->after('hobbies_interests');
            $table->string('dating_style')->nullable()->after('height');
            $table->string('ideal_type')->nullable()->after('dating_style');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['height', 'dating_style', 'ideal_type']);
        });
    }
};