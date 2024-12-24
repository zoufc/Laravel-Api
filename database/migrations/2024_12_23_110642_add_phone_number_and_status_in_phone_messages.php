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
        Schema::table('phone_messages', function (Blueprint $table) {
            $table->string('phoneNumber');
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phone_messages', function (Blueprint $table) {
            $table->dropColumn('phoneNumber');
            $table->dropColumn('active');
        });
    }
};
