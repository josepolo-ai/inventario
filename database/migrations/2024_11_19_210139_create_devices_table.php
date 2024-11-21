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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('office');
            $table->string('dni');
            $table->string('fullname');
            $table->string('charge');
            $table->string('ip');
            $table->string('mac');
            $table->string('port');
            $table->string('type');
            $table->boolean('is_ugel');
            $table->string('connection_type');
            $table->string('use_type');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
