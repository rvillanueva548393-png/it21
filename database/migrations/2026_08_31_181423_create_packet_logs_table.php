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
        Schema::create('packet_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source_ip')->nullable();
            $table->string('dest_ip')->nullable();
            $table->string('protocol')->nullable();
            $table->string('source_mac')->nullable();
            $table->string('dest_mac')->nullable();
            $table->integer('length')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packet_logs');
    }
};
