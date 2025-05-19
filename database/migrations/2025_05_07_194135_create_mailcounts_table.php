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
        Schema::create('mailcounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('domain_id');
            $table->unsignedBigInteger('email_id');
            $table->unsignedBigInteger('sent')->default(1);
            $table->unsignedBigInteger('delivered')->default(1);
            $table->unsignedBigInteger('queued')->default(1);
            $table->unsignedBigInteger('deferred')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailcounts');
    }
};
