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
        Schema::create('zones_machine', function (Blueprint $table) {
            $table->id();
            $table->integer('zone_id');
            $table->string('ip');
            $table->text('instance_id');
            $table->string('hostname')->nullable();
            $table->integer('status')->default('1');
            $table->integer('port')->default('22');
            $table->string('username')->default('root');
            $table->text('ssh_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones_machine');
    }
};
