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
        Schema::create('delivers', function (Blueprint $table) {
            $table->id();
            $table->string('code_deliver');
            $table->enum('type_deliver', ['Offline', 'Online']);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('address_id')->nullable()->constrained();
            $table->foreignId('service_id')->constrained();
            $table->text('destination_address');
            $table->float('kilometer');
            $table->float('weight');
            $table->double('total_price');
            $table->timestamp('date_pickup')->nullable();
            $table->timestamp('date_sent');
            $table->timestamp('date_received')->nullable();
            $table->enum('status_deliver', ['Pickup', 'Waiting', 'Sent', 'Arrived']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivers');
    }
};
