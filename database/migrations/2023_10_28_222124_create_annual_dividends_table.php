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
        Schema::create('annual_dividends', function (Blueprint $table) {
            $table->id();
            $table->double('total_amount', 8, 2)->default(0);
            $table->double('total_dividend', 8, 2)->default(0);
            $table->string('year');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_dividends');
    }
};
