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
        Schema::create('monitoringdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_id')->constrained()->onDelete('cascade');
            $table->string('time');
            $table->string('vm');
            $table->string('queue');
            $table->string('remark')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoringdetails');
    }
};
