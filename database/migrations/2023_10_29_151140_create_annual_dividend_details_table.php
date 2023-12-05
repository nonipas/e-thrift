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
        Schema::create('annual_dividend_details', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('annual_dividend_id');
            $table->integer('year');
            $table->integer('month_id');
            $table->double('amount', 8, 2)->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_dividend_details');
    }
};
