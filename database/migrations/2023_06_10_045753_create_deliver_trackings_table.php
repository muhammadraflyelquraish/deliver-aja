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
        Schema::create('deliver_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deliver_id')->constrained();
            $table->text('current_location');
            $table->timestamp('date_arrived');
            $table->enum('type_tracking', ['Dalam Perjalanan', 'Sampai']);
            $table->text('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliver_trackings');
    }
};
