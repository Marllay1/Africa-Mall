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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->string('icon')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        // Railway only runs `migrate --force` on deploy (never `db:seed`), so the
        // methods the checkout flow already relies on must be provisioned here.
        DB::table('payment_methods')->insert([
            ['code' => 'orange_money', 'label' => 'Orange Money', 'icon' => '📱', 'position' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'moov_money', 'label' => 'Moov Money', 'icon' => '📱', 'position' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'wave', 'label' => 'Wave', 'icon' => '📱', 'position' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'carte', 'label' => 'Carte bancaire', 'icon' => '💳', 'position' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'livraison', 'label' => 'Paiement à la livraison', 'icon' => '🚚', 'position' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
