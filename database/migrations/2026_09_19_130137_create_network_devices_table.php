<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('network_devices', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address')->unique();
            $table->string('ip_address');
            $table->string('hostname')->nullable()->default('Unknown Device');
            $table->string('vendor')->nullable()->default('Generic');
            $table->string('device_type')->nullable()->default('Endpoint');
            $table->string('status')->default('Active');
            $table->timestamp('last_seen')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('network_devices');
    }
};
