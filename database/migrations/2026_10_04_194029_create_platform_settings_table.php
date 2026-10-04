<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->unsignedInteger('min_withdrawal_amount')->default(1);
            $table->string('support_email')->nullable();
            $table->timestamps();
        });

        // Single-row settings table; Railway only runs `migrate --force` on deploy
        // (never `db:seed`), so the one row the app relies on is inserted here.
        DB::table('platform_settings')->insert([
            'maintenance_mode' => false,
            'min_withdrawal_amount' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
