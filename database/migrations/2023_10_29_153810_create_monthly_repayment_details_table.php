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
        Schema::create('monthly_repayment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_repayment_id');
            $table->integer('member_id');
            $table->integer('loan_id');
            $table->double('amount', 8, 2)->default(0);
            $table->string('month');
            $table->string('year');
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
        Schema::dropIfExists('monthly_repayment_details');
    }
};
