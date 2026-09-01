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
        Schema::create('threat_logs', function (Blueprint $table) {
            $table->id();
            $table->string('attack_type');
            $table->string('severity');
            $table->string('attacker_ip');
            $table->string('victim_ip')->nullable();
            $table->string('attacker_mac')->nullable();
            $table->string('status')->default('Active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threat_logs');
    }
};
