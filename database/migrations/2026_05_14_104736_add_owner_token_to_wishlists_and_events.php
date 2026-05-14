<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->string('owner_token', 36)->nullable()->after('birthday_date');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('owner_token', 36)->nullable()->after('birthday_date');
        });
    }

    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn('owner_token');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('owner_token');
        });
    }
};
