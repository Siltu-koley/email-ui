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
        Schema::create('user_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            $table->unsignedBigInteger('wildduck_userid');
            $table->unsignedBigInteger('domain_id');
            $table->string('email');
            $table->integer('main')->default(1);
            $table->integer('allowWildcard')->default(0);
            $table->text('tags')->nullable();
            $table->text('metaData')->nullable();
            $table->text('internalData')->nullable();
            $table->string('sess')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_emails');
    }
};
