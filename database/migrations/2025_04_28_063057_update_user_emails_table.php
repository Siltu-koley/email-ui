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
        Schema::table('user_emails', function (Blueprint $table) {
            // Change the data type of 'wildduck_userid' to varchar(255)
            $table->string('wildduck_userid', 255)->change();

            // Add 'inbox_id' column as an unsigned integer (adjust based on your requirements)
            $table->string('inbox_id')->nullable()->after('ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_emails', function (Blueprint $table) {
            // Reverse the change for 'wildduck_userid' column
            $table->integer('wildduck_userid')->change();

            // Drop the 'inbox_id' column
            $table->dropColumn('inbox_id');
        });
    }
};
