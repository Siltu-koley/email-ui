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
        Schema::create('dkim_keys', function (Blueprint $table) {
            $table->id();
            $table->integer('domain_id');
            $table->string('domain');
            $table->string('selector')->default('default');
            $table->string('txt_name');
            $table->text('txt_value');
            $table->text('private_key');
            $table->text('public_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dkim_keys');
    }
};
