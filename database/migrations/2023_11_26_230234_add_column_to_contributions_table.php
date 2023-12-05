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
        Schema::table('contributions', function (Blueprint $table) {
            //add no of months column
            $table->integer('no_of_months')->after('amount')->default(0);
            //add previous months columns
            $table->integer('previous_months_no')->after('no_of_months')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            //
        });
    }
};
